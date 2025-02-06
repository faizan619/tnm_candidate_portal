<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;
use App\Models\Requirement;
use App\Models\RequirementQualification;
use App\Models\RequirementAgeLimit;
use App\Models\ProjectNotification;
use App\Models\ProjectDocument;
use App\Models\ProjectCaste;
use App\Models\Pincode;
use App\Models\CandidateUser;
use App\Models\LookupValue;
use App\Models\ApplicationPersonal;
use App\Models\ApplicationExperience;
use App\Models\ApplicationEducation;
use App\Models\ApplicationDocument;
use App\Models\ApplicationCertification;
use App\Models\CandidateDocument;
use App\Models\Template;
use App\Models\ClientHo;
use App\Models\CandidateIP;
use App\Helpers\EmailHelper;
use App\Helpers\SMSHelper;
use Carbon\Carbon;

use Auth;


class ContractualController extends Controller
{
    public function index()
    {
        $currentDate = \Carbon\Carbon::now();

    $projects = Project::with([
            'client',
            'requirements' => function ($query) use ($currentDate) {
                $query->where('website_publish_date', '<=', $currentDate);
            },
            'projectNotifications'
        ])
        ->where('status', 1)
        ->orderBy('start_date', 'desc')
        ->paginate(10);

        $filters = Project::with(['client' => function ($query) {
                $query->select('id', 'short_name');
            }])
            ->select('id', 'client_id', 'title')
            ->where('status', 1)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('contractuals.index', compact('projects','filters'));
    }

    public function search(Request $request)
    {
        $searchText = $request->query('search');

        // Query projects based on search criteria
        $projects = Project::with(['client', 'requirements'])
            ->where('title', 'like', "%$searchText%")
            ->orWhereHas('client', function ($query) use ($searchText) {
                $query->where('short_name', 'like', "%$searchText%");
            })
            ->orWhereHas('requirements', function ($query) use ($searchText) {
                $query->where('position', 'like', "%$searchText%")
                      ->orWhere('locations', 'like', "%$searchText%");
            })
            ->orderBy('start_date', 'desc')
            ->get();

        
        
        return view('contractuals.search_project', compact('projects'));
    }



  public function filterProjects(Request $request)
{
    $jobType = $request->input('job_type');
    $clientName = $request->input('client_name');
    $projectTitle = $request->input('project_title');

    // Step 1: Query Projects with basic filters
    $query = Project::with('requirements', 'projectNotifications')
        ->where('status', 1);

    if ($projectTitle) {
        $query->where('title', 'like', '%' . $projectTitle . '%');
    }

    // Step 2: Apply pagination
    $projects = $query->orderBy('start_date', 'desc')->paginate(10);

    // Step 3: Fetch related client data
    $clientIds = $projects->pluck('client_id')->unique()->filter();
    $clientsQuery = ClientHo::whereIn('id', $clientIds);
    if ($clientName) {
        $clientsQuery->where('short_name', 'like', '%' . $clientName . '%');
    }
    $clients = $clientsQuery->get()->keyBy('id');

    // Step 4: Map Project data with client name and filter requirements by jobType
    $projects->getCollection()->transform(function ($project) use ($clients, $jobType) {
        // Filter the requirements based on job type, if provided
        $filteredRequirements = $project->requirements->filter(function ($requirement) use ($jobType) {
            return !$jobType || $requirement->job_type == $jobType;
        });

        // If there are no requirements left after filtering, skip this project
        if ($filteredRequirements->isEmpty()) {
            return null;  // Skip the project if no valid requirements remain
        }

        // Return transformed project data including filtered requirements
        return (object) [
            'id' => $project->id,
            'title' => $project->title,
            'description' => $project->description,
            'start_date' => $project->start_date,
            'client_name' => $clients->get($project->client_id)?->short_name ?? 'N/A',
            'requirements' => $filteredRequirements,  // Only include filtered requirements
            'project_notifications' => $project->projectNotifications,
        ];
    });

    // Step 5: Filter out any null projects after transformation
    $projects->getCollection()->filter(function ($project) {
        return $project !== null;  // Remove null projects
    });

    // Step 6: Fetch filter options for the view
    $filters = Project::with(['client' => function ($query) {
        $query->select('id', 'short_name');
    }])
    ->select('id', 'client_id', 'title')
    ->where('status', 1)
    ->orderBy('start_date', 'desc')
    ->get();

    // Return the view with pagination links
    return view('contractuals.index', [
        'projects' => $projects,
        'filters' => $filters,
    ]);
}




 
    public function getRequirement($id,$position)
    {
        //$requirements=Requirement::where('project_id',$id)->where('position',$position)->first();
        $requirements=Requirement::findOrFail($id);
        
        $age_limits="";
        if($requirements)
        {
            //qualifications=RequirementQualification::where('requirement_id',$id)->get();
            $age_limits=RequirementAgeLimit::where('requirement_id',$requirements->id)->get();
        }
        $projects=Project::select('id','application_mode','terms_conditions','general_instructions_candidate')->findOrFail($requirements->project_id);
        $projects_doc=ProjectDocument::where('project_id',$requirements->project_id)->get();
        
        return view('contractuals.requirement_details',compact('requirements','age_limits','projects','projects_doc'));
    }
    public function getShareRequirement($id,$position)
    {
        $requirements=Requirement::where('project_id',$id)->where('position',$position)->first();
        $age_limits="";
        if($requirements)
        {
            //qualifications=RequirementQualification::where('requirement_id',$id)->get();
            $age_limits=RequirementAgeLimit::where('requirement_id',$requirements->id)->get();
        }
        $projects=Project::select('id','application_mode','terms_conditions','general_instructions_candidate')->findOrFail($id);
        $projects_doc=ProjectDocument::where('project_id',$projects->id)->get();
        
        return view('contractuals.requirement_share',compact('requirements','age_limits','projects','projects_doc'));
        
       
    }

    public function shareViaEmail(Request $request)
    {
        $email = $request->email;
        $url = $request->url;
        $subject = 'Check this out';
        $message = "Here's something interesting I wanted to share with you: $url";

         EmailHelper::setMailConfig();
         
        try {
            Mail::raw($message, function ($msg) use ($email, $subject) {
                $msg->to($email)->subject($subject);
            });

            return redirect()->back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }

    }

    public function shareViaWhatsApp(Request $request)
    {
        $mobile = $request->mobile;
        $url = $request->url;
        $message = "Check this out: $url";
        // Redirecting to WhatsApp API
        $whatsappLink = "https://api.whatsapp.com/send?phone=$mobile&text=" . urlencode($message);

        return redirect($whatsappLink);
    }

    public function getNotifications($project_id) {
        $notifications = ProjectNotification::where('project_id', $project_id)->get();
        return response()->json($notifications);
    }

    public function fetchStateDistrict(Request $request)
    {
		
         $pincode = $request->input('pincode');
		
        $data = Pincode::where('pincode', $pincode)->first();
        if (!$data) {
           $data=['error'=>'Pincode not found'];
        }
        
        return response()->json($data);
    }

    //Application step #1
   public function application_personal_details($id, $position)
{
    // Fetch the requirement and associated project
    $requirements = Requirement::findOrFail($id);
    $project = Project::findOrFail($requirements->project_id);

    // Check if the candidate has already applied for this requirement
    $applicationPersonal = ApplicationPersonal::where('candidateuser_id', Auth::user()->id)
                                              ->where('requirement_id', $id)
                                              ->first();

    // Check if the user has exceeded the number of positions per candidate for this project
    if (!$applicationPersonal) {
        $positionsApplied = ApplicationPersonal::where('candidateuser_id', Auth::user()->id)
                                               ->whereHas('requirement', function($query) use ($project) {
                                                   $query->where('project_id', $project->id);
                                               })
                                               ->count();

        if ($positionsApplied >= $project->positions_per_candidate) {
            return redirect()->back()->with('error', 'You have exceeded the number of positions required for the project. <a href="'.route('myapplication').'">Click here</a> for the positions you have applied for.');
        }
    }

    // Fetch the necessary data for the form
    $project_caste = ProjectCaste::where('project_id', $requirements->project_id)->get();
    $genders = LookupValue::where('type_id', 3)->get();
    $religions = LookupValue::where('type_id', 7)->get();
    $marital_status = LookupValue::where('type_id', 23)->get();
    $candidate_user = CandidateUser::findOrFail(Auth::user()->id);
    $selectedLocations = $applicationPersonal ? json_decode($applicationPersonal->locations, true) : [];

    // Render the form with appropriate data
    return view('contractuals.application.personal_details', compact('position', 'requirements', 'project_caste', 'genders', 'religions', 'marital_status', 'candidate_user', 'applicationPersonal', 'selectedLocations'))
        ->with('info', $applicationPersonal ? 'You are updating your existing application.' : 'You are creating a new application.');
}



    public function application_personal_details_store(Request $request)
    {
       /* $request->validate([
            'captcha' => ['required', 'captcha']
            ], [
                'captcha.captcha' => 'The CAPTCHA is incorrect. Please refresh the captcha and try again.',
        ]);*/

        $dob = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');

        // Retrieve the existing record or create a new instance
        $applicationPersonal = ApplicationPersonal::firstOrNew([
            'candidateuser_id' => auth()->id(),
            'requirement_id' => $request->requirement_id,
        ]);



        // Update or set attributes
        $applicationPersonal->job_type = $request->job_type;
        $applicationPersonal->candidateuser_id = auth()->id();
        $applicationPersonal->requirement_id = $request->requirement_id;
        $applicationPersonal->post_applied_for = $request->position;
        $applicationPersonal->locations = $request->location;
        $applicationPersonal->name = $request->name;
        $applicationPersonal->id_proof_type = $request->id_proof_type;
        $applicationPersonal->id_proof_no = $request->id_proof_no;
        $applicationPersonal->caste = $request->caste;
        $applicationPersonal->date_of_birth = $dob;
        $applicationPersonal->place_of_birth = $request->birth_place;
        $applicationPersonal->gender = $request->gender;
        $applicationPersonal->physically_challenged = $request->physically_challenged;
        $applicationPersonal->percentage_of_disability = $request->percentage_of_disability;
        $applicationPersonal->religion = $request->religion;
        $applicationPersonal->present_address = $request->present_address;
        $applicationPersonal->present_pincode = $request->present_pincode;
        $applicationPersonal->present_state = $request->present_state;
        $applicationPersonal->present_district = $request->present_district;
        $applicationPersonal->permanent_address = $request->permanent_address;
        $applicationPersonal->permanent_pincode = $request->permanent_pincode;
        $applicationPersonal->permanent_state = $request->permanent_state;
        $applicationPersonal->permanent_district = $request->permanent_district;
        $applicationPersonal->marital_status = $request->marital_status;
        $applicationPersonal->spouse_name = $request->spouse;
        $applicationPersonal->father_name = $request->father_name;
        $applicationPersonal->mother_name = $request->mother_name;
        $applicationPersonal->mobile = $request->mobile;
        $applicationPersonal->email = $request->email;
        $applicationPersonal->status = "Incomplete";

        $applicationPersonal->save();
        $application_id = $applicationPersonal->id;

        return redirect()->route('application.education', compact('application_id'))->with('success', 'Application personal details saved successfully.');
    }


    public function application_education($application_id)
    {
        $application=ApplicationPersonal::select('id','requirement_id','post_applied_for','locations')
        ->where('id', $application_id)
        ->where('candidateuser_id', Auth::id()) 
        ->first();
        $qualifications=RequirementQualification::where('requirement_id',$application->requirement_id)->get();
        
        $educationRecords = ApplicationEducation::where('application_id', $application_id)->get();
        $certificationRecords = ApplicationCertification::where('application_id', $application_id)->get();
        return view('contractuals.application.education', compact('application', 'qualifications', 'educationRecords','certificationRecords'));
    }

    

    public function application_education_store(Request $request)
    {

       /* $request->validate([
            'captcha' => ['required', 'captcha']
            ], [
                'captcha.captcha' => 'The CAPTCHA is incorrect. Please refresh the captcha and try again.',
        ]);*/

       foreach ($request['stream'] as $key => $value) {
        $educationRecord = ApplicationEducation::where('application_id', $request['application_id'])
                                               ->where('stream', $request['stream'][$key])
                                               ->first();

        if ($educationRecord) {
            // Update existing record
            $educationRecord->update([
                'year_of_passing' => $request['year_of_passing'][$key],
                'university_institutions' => $request['university_board_institution'][$key],
                'grade' => $request['grade'][$key],
                'mode' => $request['mode'][$key],
            ]);
        } else {
            // Create new record
            ApplicationEducation::create([
                'application_id' => $request['application_id'],
                'stream' => $request['stream'][$key],
                'year_of_passing' => $request['year_of_passing'][$key],
                'university_institutions' => $request['university_board_institution'][$key],
                'grade' => $request['grade'][$key],
                'mode' => $request['mode'][$key],
            ]);
        }
    }

    // Handle Certification Records
	if (!empty($request['course'])) {
    foreach ($request['course'] as $key => $value) {
        // Check if the course is not empty
        if (!empty($request['course'][$key])) {
            $certificationRecord = ApplicationCertification::where('application_id', $request['application_id'])
                                                            ->where('course', $request['course'][$key])
                                                            ->first();

            if ($certificationRecord) {
                // Update existing record
                $certificationRecord->update([
                    'subject' => $request['subject'][$key],
                    'percentage' => $request['percentage'][$key],
                    'passing_year' => $request['cer_passing_year'][$key],
                    'institute' => $request['cer_institute'][$key],
                    'duration' => $request['duration'][$key],
                    'mode' => isset($request['cer_mode'][$key]) ? $request['cer_mode'][$key] : null,
                ]);
            } else {
                // Create new record
                ApplicationCertification::create([
                    'application_id' => $request['application_id'],
                    'course' => $request['course'][$key],
                    'subject' => $request['subject'][$key],
                    'percentage' => $request['percentage'][$key],
                    'passing_year' => $request['cer_passing_year'][$key],
                    'institute' => $request['cer_institute'][$key],
                    'duration' => $request['duration'][$key],
                    'mode' => isset($request['cer_mode'][$key]) ? $request['cer_mode'][$key] : null,
                ]);
            }
        }
    }
}

    return redirect()->route('application.experience', ['application_id' => $request->application_id])
                     ->with('success', 'Education details saved successfully.');
    }

    public function application_experience($application_id)
    {
        $application = ApplicationPersonal::select('id', 'post_applied_for', 'locations','requirement_id')
        ->where('id', $application_id)
        ->where('candidateuser_id', Auth::id()) 
        ->first();

        
         $workExperienceData = ApplicationExperience::where('application_id', $application_id)->get();     

        return view('contractuals.application.work_experience', compact('application', 'workExperienceData'));
    }

    public function application_experience_store(Request $request)
    {
        // Validate the request
        /*$request->validate([
            'captcha' => ['required', 'captcha']
            ], [
                'captcha.captcha' => 'The CAPTCHA is incorrect. Please refresh the captcha and try again.',
        ]);*/

        $formData = $request->all();

        // Extract specific arrays from form data
        $applicationId = $formData['application_id'];
        $experienceIds = $formData['experience_id'] ?? []; // Assuming you pass IDs for existing records
        $fromArray = $formData['from'] ?? [];
        $toArray = $formData['to'] ?? [];
        $companyArray = $formData['company'] ?? [];
        $designationArray = $formData['designation'] ?? [];
        $workTypeArray = $formData['work_type'] ?? [];
        $ctcArray = $formData['ctc'] ?? [];
        $responsibilitiesArray = $formData['responsibilities'] ?? [];

        // Get existing records for the application
        $existingExperiences = ApplicationExperience::where('application_id', $applicationId)->get()->keyBy('id');

        // Loop through the form data to update or create records
        foreach ($companyArray as $key => $company) {
            $experienceId = $experienceIds[$key] ?? null;
            
            if ($experienceId && isset($existingExperiences[$experienceId])) {
                // Update existing record
                $workExperience = $existingExperiences[$experienceId];
            } else {
                // Create a new record
                $workExperience = new ApplicationExperience();
                $workExperience->application_id = $applicationId;
            }

            // Set attributes based on array values
            $workExperience->from_date = \Carbon\Carbon::createFromFormat('d/m/Y', $fromArray[$key])->format('Y-m-d');
            $workExperience->to_date = \Carbon\Carbon::createFromFormat('d/m/Y', $toArray[$key])->format('Y-m-d');
            $workExperience->company = $company;
            $workExperience->designation = $designationArray[$key] ?? null;
            $workExperience->work_type = $workTypeArray[$key] ?? null;
            $workExperience->ctc = $ctcArray[$key] ?? null;
            $workExperience->responsibilities = $responsibilitiesArray[$key] ?? null;

            // Save the work experience entry
            $workExperience->save();
            
            // Remove the updated/created record from the existing experiences collection
            if ($experienceId) {
                $existingExperiences->forget($experienceId);
            }
        }

        // Delete removed records
        foreach ($existingExperiences as $experienceId => $workExperience) {
            $workExperience->delete();
        }

        $applicationPersonal = ApplicationPersonal::find($applicationId);
        if ($applicationPersonal) {
            $applicationPersonal->total_experience = $request->total_experience;
            $applicationPersonal->save();
        }

        // Redirect or return a response as needed
        return response()->json(['success' => 'Work experience details saved successfully.', 'application_id' => $applicationId]);

    }

    

    public function application_upload($application_id)
    {

        $application=ApplicationPersonal::select('id','requirement_id','post_applied_for','locations','physically_challenged')->findOrFail($application_id);
        $requirements=Requirement::select('id','document_uploaded','age_proof_mandatory')->find($application->requirement_id);
        
        $documentUpload= explode(',', $requirements->document_uploaded);
        $candidateUser=CandidateUser::select('id','photo','signature')->findOrFail(Auth::User()->id);
        $oneYearAgo = Carbon::now()->subYear();
        $applicationDocuments=ApplicationDocument::where('application_id',$application_id)->get();
        
        return view('contractuals.application.documents_upload', compact('application','documentUpload','candidateUser','oneYearAgo','requirements','applicationDocuments'));
    }

    

   

public function application_upload_store(Request $request)
{
    $applicationId = $request->input('application_id');
    $requirementId = $request->input('requirement_id');

    // Save documents
    if ($request->hasFile('upload')) {
        foreach ($request->file('upload') as $index => $file) {
            // Skip files that are handled separately below
            if ($index == 'age_proof' || $index == 'disability_certificate') {
                continue;
            }
            $documentType = $request->input('document_type')[$index];
            
            // Retrieve and delete existing files
            $existingDocuments = ApplicationDocument::where('application_id', $applicationId)->where('document_type', $documentType)->get();
            foreach ($existingDocuments as $existingDocument) {
                Storage::disk('public')->delete($existingDocument->document_file);
            }
            $existingDocuments->each->delete();
            
            $existingCandidateDocuments = CandidateDocument::where('application_id', $applicationId)->where('document_type', $documentType)->get();
            foreach ($existingCandidateDocuments as $existingCandidateDocument) {
                Storage::disk('public')->delete($existingCandidateDocument->file_path);
            }
            $existingCandidateDocuments->each->delete();

            // Store new document
            $filePath = $file->store('documents', 'public');
            ApplicationDocument::create([
                'application_id' => $applicationId,
                'document_type' => $documentType,
                'document_file' => $filePath
            ]);
            CandidateDocument::create([
                'candidate_user_id' => Auth::user()->id, 
                'application_id' => $applicationId,
                'document_type' => $documentType,
                'file_path' => $filePath
            ]);
        }
    }

    // Save age proof document if required
    if ($request->hasFile('upload.age_proof')) {
        $existingDocuments = ApplicationDocument::where('application_id', $applicationId)->where('document_type', 'Age Proof')->get();
        foreach ($existingDocuments as $existingDocument) {
            Storage::disk('public')->delete($existingDocument->document_file);
        }
        $existingDocuments->each->delete();

        $existingCandidateDocuments = CandidateDocument::where('application_id', $applicationId)->where('document_type', 'Age Proof')->get();
        foreach ($existingCandidateDocuments as $existingCandidateDocument) {
            Storage::disk('public')->delete($existingCandidateDocument->file_path);
        }
        $existingCandidateDocuments->each->delete();

        // Store new age proof document
        $filePath = $request->file('upload.age_proof')->store('documents', 'public');
        ApplicationDocument::create([
            'application_id' => $applicationId,
            'document_type' => 'Age Proof',
            'document_file' => $filePath
        ]);
        CandidateDocument::create([
            'candidate_user_id' => Auth::user()->id, 
            'application_id' => $applicationId,
            'document_type' => 'Age Proof',
            'file_path' => $filePath
        ]);
    }

    // Save disability document if required
    if ($request->hasFile('upload.disability_certificate')) {
        $existingDocuments = ApplicationDocument::where('application_id', $applicationId)->where('document_type', 'Disability Certificate')->get();
        foreach ($existingDocuments as $existingDocument) {
            Storage::disk('public')->delete($existingDocument->document_file);
        }
        $existingDocuments->each->delete();

        $existingCandidateDocuments = CandidateDocument::where('application_id', $applicationId)->where('document_type', 'Disability Certificate')->get();
        foreach ($existingCandidateDocuments as $existingCandidateDocument) {
            Storage::disk('public')->delete($existingCandidateDocument->file_path);
        }
        $existingCandidateDocuments->each->delete();

        // Store new disability document
        $filePath = $request->file('upload.disability_certificate')->store('documents', 'public');
        ApplicationDocument::create([
            'application_id' => $applicationId,
            'document_type' => 'Disability Certificate',
            'document_file' => $filePath
        ]);
        CandidateDocument::create([
            'candidate_user_id' => Auth::user()->id, 
            'application_id' => $applicationId,
            'document_type' => 'Disability Certificate',
            'file_path' => $filePath
        ]);
    }

    // Update photo
    if ($request->hasFile('photo')) {
        $existingDocuments = ApplicationDocument::where('application_id', $applicationId)->where('document_type', 'photo')->get();
        foreach ($existingDocuments as $existingDocument) {
            Storage::disk('public')->delete($existingDocument->document_file);
        }
        $existingDocuments->each->delete();

        $existingCandidateDocuments = CandidateDocument::where('application_id', $applicationId)->where('document_type', 'photo')->get();
        foreach ($existingCandidateDocuments as $existingCandidateDocument) {
            Storage::disk('public')->delete($existingCandidateDocument->file_path);
        }
        $existingCandidateDocuments->each->delete();

        $photoPath = $request->file('photo')->store('photos', 'public');
        ApplicationDocument::create([
            'application_id' => $applicationId,
            'document_type' => 'photo',
            'document_file' => $photoPath
        ]);
        CandidateDocument::create([
            'candidate_user_id' => Auth::user()->id, 
            'application_id' => $applicationId,
            'document_type' => 'photo',
            'file_path' => $photoPath
        ]);
    }

    // Update signature
    if ($request->hasFile('signature')) {
        $existingDocuments = ApplicationDocument::where('application_id', $applicationId)->where('document_type', 'signature')->get();
        foreach ($existingDocuments as $existingDocument) {
            Storage::disk('public')->delete($existingDocument->document_file);
        }
        $existingDocuments->each->delete();

        $existingCandidateDocuments = CandidateDocument::where('application_id', $applicationId)->where('document_type', 'signature')->get();
        foreach ($existingCandidateDocuments as $existingCandidateDocument) {
            Storage::disk('public')->delete($existingCandidateDocument->file_path);
        }
        $existingCandidateDocuments->each->delete();

        $signaturePath = $request->file('signature')->store('signatures', 'public');
        ApplicationDocument::create([
            'application_id' => $applicationId,
            'document_type' => 'signature',
            'document_file' => $signaturePath
        ]);
        CandidateDocument::create([
            'candidate_user_id' => Auth::user()->id, 
            'application_id' => $applicationId,
            'document_type' => 'signature',
            'file_path' => $signaturePath
        ]);
    }

    // Save new CV if uploaded
    if ($request->hasFile('cv_upload')) {
        $existingDocuments = ApplicationDocument::where('application_id', $applicationId)->where('document_type', 'cv')->get();
        foreach ($existingDocuments as $existingDocument) {
            Storage::disk('public')->delete($existingDocument->document_file);
        }
        $existingDocuments->each->delete();

        $existingCandidateDocuments = CandidateDocument::where('application_id', $applicationId)->where('document_type', 'cv')->get();
        foreach ($existingCandidateDocuments as $existingCandidateDocument) {
            Storage::disk('public')->delete($existingCandidateDocument->file_path);
        }
        $existingCandidateDocuments->each->delete();

        // Store new CV
        $cvPath = $request->file('cv_upload')->store('cv', 'public');
        ApplicationDocument::create([
            'application_id' => $applicationId,
            'document_type' => 'cv',
            'document_file' => $cvPath
        ]);
        CandidateDocument::create([
            'candidate_user_id' => Auth::user()->id, 
            'application_id' => $applicationId,
            'document_type' => 'cv',
            'file_path' => $cvPath
        ]);
    }

    $applicationPersonal = ApplicationPersonal::find($applicationId);
    $applicationPersonal->status = 'Complete';
    $applicationPersonal->save();

    $candidateUserId = Auth::User()->id;
    $applicationPersonal = ApplicationPersonal::where('candidateuser_id', $candidateUserId)->where('id', $applicationId)->first();
    $applicationEducation = ApplicationEducation::where('application_id', $applicationId)->get();
    $applicationExperience = ApplicationExperience::where('application_id', $applicationId)->get();
    $applicationDocument = ApplicationDocument::where('application_id', $applicationId)->get();

    $requirements = Requirement::findOrFail($requirementId);
    $project = Project::select('candidate_undertaking')->findOrFail($requirements->project_id);

    return view('contractuals.application.preview', compact('applicationPersonal', 'applicationEducation', 'applicationExperience', 'applicationDocument', 'project'));
}



    public function applicationPreview($applicationId)
    {

        $candidateUserId = Auth::User()->id;
        $applicationPersonal = ApplicationPersonal::where('candidateuser_id', $candidateUserId)->where('id', $applicationId)->first();
        $applicationEducation = ApplicationEducation::where('application_id', $applicationId)->get();
        $applicationExperience = ApplicationExperience::where('application_id', $applicationId)->get();
        $applicationDocument = ApplicationDocument::where('application_id', $applicationId)->get();

        $requirements=Requirement::findOrFail($applicationPersonal->requirement_id);
        $project=Project::select('candidate_undertaking')->findOrFail($requirements->project_id);
        
        
        return view('contractuals.application.preview', compact('applicationPersonal', 'applicationEducation', 'applicationExperience', 'applicationDocument','project'));
    }

    public function finalsubmit(Request $request, $application_id)
    {

        $applicationPersonal = ApplicationPersonal::find($application_id);
        $applicationPersonal->status = 'Submitted';
        $applicationPersonal->updated_at = Carbon::now();
        $applicationPersonal->save();

        $candidateIP=new CandidateIP();
        $candidateIP->candidate_user_id=Auth::User()->id;
        $candidateIP->application_id=$application_id;
        $candidateIP->ip_address=$request->ip();
        $candidateIP->save();

        // Send email
		 EmailHelper::setMailConfig();
        $emailTemplate = Template::where('type', 'Email')
            ->where('name', 'Application Submission')
            ->where('status', 1)
            ->first();
		
		$applicationPersonal->id=3;
        if ($emailTemplate) {
            $emailMessage = $emailTemplate->description;
            $emailMessage = str_replace('[name]', Auth::User()->name, $emailMessage);
            $emailMessage = str_replace('[position]', $applicationPersonal->post_applied_for, $emailMessage);
            $emailMessage = str_replace('[app_ref_no]', $applicationPersonal->id, $emailMessage);
			
            $email = Auth::User()->email;
            /*Mail::html($emailMessage, function ($msg) use ($email) {
                $msg->to($email)->subject('Application Submission with TNMHR.');
            });*/
        }

        // Send SMS
        $smsTemplate = Template::where('type', 'SMS')
            ->where('name', 'Application Submission')
            ->where('status', 1)
            ->first();
		
        if ($smsTemplate) {
            $smsMessage = strip_tags($smsTemplate->description);
			$smsMessage = str_replace('[position]', $applicationPersonal->post_applied_for, $smsMessage);
            $smsMessage = str_replace('[app_ref_no]', $applicationPersonal->id, $smsMessage);
            $mobile = Auth::User()->mobile;
            SMSHelper::setSMSConfig($mobile, $smsMessage);
        }

        return redirect()->route('application.success')->with('success', 'Application submitted successfully.');

    }


   
}

