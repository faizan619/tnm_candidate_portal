<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CandidateUser;
use App\Models\CandidateQualification;
use App\Models\CandidateExperience;
use App\Models\CandidateCV;
use App\Models\CandidateDocument;
use App\Models\LookupValue;
use App\Models\CandidateLanguage;
use Auth;

class CandidateProfileController extends Controller
{
    
    public function index()
    {
        $userId=Auth::User()->id;
        $candidateUser=CandidateUser::findOrFail($userId);
        $candidateLanguages=CandidateLanguage::where('candidate_user_id',$userId)->get();
        $candidateQualification=CandidateQualification::where('candidate_user_id',$userId)->get();
        $candidateExperience=CandidateExperience::where('candidate_user_id',$userId)->get();
        //$candidateCV=CandidateCV::where('candidate_user_id',$userId)->latest()->first();
        $candidateDocuments=CandidateDocument::where('candidate_user_id',$userId)->get();
        return view('candidate.profile',compact('candidateUser','candidateLanguages','candidateQualification','candidateExperience','candidateDocuments'));
    }

    public function personalDetailsEdit($candidateID)
    {
        $candidate=CandidateUser::findOrFail($candidateID);
        $candidateDocuments=CandidateDocument::where('candidate_user_id',$candidateID)->get();
        //lookup type for gender is 3
        $genders=LookupValue::where('type_id',3)->get();
        $industries=LookupValue::where('type_id',4)->get();                
        //lookup type for designations is 20
         $languages=LookupValue::where('type_id',28)->get(); 
        $candidateLanguages = CandidateLanguage::where('candidate_user_id', $candidateID)->get();
        return view('candidate.personal_details_edit',compact('candidate','genders','industries','candidateDocuments','languages','candidateLanguages'));
    }
    
    public function personalDetailsUpdate(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'present_address' => 'nullable|string',
            'present_pincode' => 'nullable|string|max:6',
            'present_state' => 'nullable|string',
            'present_district' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'permanent_pincode' => 'nullable|string|max:6',
            'permanent_state' => 'nullable|string',
            'permanent_district' => 'nullable|string',
            'date_of_birth' => 'required|date_format:d/m/Y',
            'industry' => 'nullable|string',
            /*'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',*/
            'captcha' => 'required|captcha'
        ],[
                'captcha.captcha' => 'The CAPTCHA is incorrect. Please try again.',
        ]);

        

        $candidate = CandidateUser::findOrFail(Auth::User()->id);
        $candidate->name = $request->name;
        $candidate->gender = $request->gender;
        $candidate->present_address = $request->present_address;
        $candidate->present_pincode = $request->present_pincode;
        $candidate->present_state = $request->present_state;
        $candidate->present_district = $request->present_district;
        $candidate->permanent_address = $request->permanent_address;
        $candidate->permanent_pincode = $request->permanent_pincode;
        $candidate->permanent_state = $request->permanent_state;
        $candidate->permanent_district = $request->permanent_district;
        $candidate->date_of_birth = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
        $candidate->industry = $request->industry;
        $candidate->save();
        
          // Fetch existing languages for the candidate and delete them
        CandidateLanguage::where('candidate_user_id', $candidate->id)->delete();

        // Check if languages are provided in the request
        if ($request->has('languages')) {
            foreach ($request->languages as $languageData) {
                if (!empty($languageData['name'])) {  // Check if 'name' is not empty
                    $language = new CandidateLanguage();
                    $language->candidate_user_id = $candidate->id;
                    $language->language = $languageData['name']; // Store the language name
                    $language->read = in_array('Read', $languageData['skills']) ? 1 : 0;
                    $language->write = in_array('Write', $languageData['skills']) ? 1 : 0;
                    $language->speak = in_array('Speak', $languageData['skills']) ? 1 : 0;
                    $language->save();
                }
            }
        }

        // Handle photo upload
        /*if ($request->hasFile('photo')) {
            if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
                Storage::disk('public')->delete($candidate->photo);
            }

            $photoPath = $request->file('photo')->store('photos', 'public');
            $candidate->photo = $photoPath;
        }

        // Handle signature upload
        if ($request->hasFile('signature')) {
            if ($candidate->signature && Storage::disk('public')->exists($candidate->signature)) {
                Storage::disk('public')->delete($candidate->signature);
            }
            $signaturePath = $request->file('signature')->store('signatures', 'public');
            $candidate->signature = $signaturePath;
        }*/


        

       return redirect()->route('myprofile')->with('alert-success', 'Personal details updated successfully!');
    } 


    public function educationEdit($candidateID)
    {
        //lookup type for industry is 10
        $streams=LookupValue::where('type_id',10)->get();     
        //lookup type for industry is 11
        $specialisations=LookupValue::where('type_id',11)->get();
        $qualifications=CandidateQualification::where('candidate_user_id',$candidateID)->get();
        return view('candidate.education_edit',compact('streams','specialisations','qualifications')); 
    }   

    public function educationUpdate(Request $request)
    {
        $candidateID = Auth::user()->id;
        
        $request->validate([
            'captcha' => ['required', 'captcha']
            ], [
                'captcha.captcha' => 'The CAPTCHA is incorrect. Please try again.',
        ]);


        $existingQualifications = CandidateQualification::where('candidate_user_id', $candidateID)->get();
        $existingQualificationIds = $existingQualifications->pluck('id')->toArray();

        $inputQualificationIds = $request->input('qualification_id', []);
        $streams = $request->input('stream', []);
        $specialisations = $request->input('specialisation', []);
        $years = $request->input('year_of_passing', []);
        $institutions = $request->input('institution', []);
        $marks = $request->input('marks_obtained', []);
        $grades = $request->input('grade', []);
        $modes = $request->input('mode', []);

        // Update or create qualifications
        foreach ($streams as $index => $stream) {
            $qualificationId = $inputQualificationIds[$index] ?? null;

            $qualificationData = [
                'stream' => $stream,
                'specialisation' => $specialisations[$index],
                'passing_year' => $years[$index],
                'institution' => $institutions[$index],
                'marks_obtained' => $marks[$index],
                'grade' => $grades[$index],
                'mode' => $modes[$index],
                'candidate_user_id' => $candidateID,
            ];

            if ($qualificationId && in_array($qualificationId, $existingQualificationIds)) {
                // Update existing qualification
                CandidateQualification::where('id', $qualificationId)->update($qualificationData);
            } else {
                // Insert new qualification
                CandidateQualification::create($qualificationData);
            }
        }

        // Delete removed qualifications
        $qualificationsToDelete = array_diff($existingQualificationIds, $inputQualificationIds);
        if (!empty($qualificationsToDelete)) {
            CandidateQualification::whereIn('id', $qualificationsToDelete)->delete();
        }

        return redirect()->route('myprofile')->with('alert-success', 'Education details updated successfully!');
    }

    public function experienceEdit($candidateID)
    {

        $experiences = CandidateExperience::where('candidate_user_id', $candidateID)->get();
        return view('candidate.experience_edit', compact('experiences'));
    }

    public function experienceUpdate(Request $request)
    {
        $candidateID = Auth::user()->id;
        
        $request->validate([
            'captcha' => ['required', 'captcha']
            ], [
                'captcha.captcha' => 'The CAPTCHA is incorrect. Please try again.',
        ]);

         $existingExperiences = CandidateExperience::where('candidate_user_id', $candidateID)->get();
        $existingExperienceIds = $existingExperiences->pluck('id')->toArray();

        $inputExperienceIds = $request->input('experience_id', []);
        $fromDates = $request->input('from', []);
        $toDates = $request->input('to', []);
        $companies = $request->input('company', []);
        $designations = $request->input('designation', []);

        // Update or create experiences
        foreach ($fromDates as $index => $from) {
            $experienceId = $inputExperienceIds[$index] ?? null;

            $experienceData = [
                'from_date' => $from,
                'to_date' => $toDates[$index],
                'company' => $companies[$index],
                'designation' => $designations[$index],
                'candidate_user_id' => $candidateID,
            ];

            if ($experienceId && in_array($experienceId, $existingExperienceIds)) {
                // Update existing experience
                CandidateExperience::where('id', $experienceId)->update($experienceData);
            } else {
                // Insert new experience
                CandidateExperience::create($experienceData);
            }
        }

        // Delete removed experiences
        $experiencesToDelete = array_diff($existingExperienceIds, $inputExperienceIds);
        if (!empty($experiencesToDelete)) {
            CandidateExperience::whereIn('id', $experiencesToDelete)->delete();
        }

        return redirect()->route('myprofile')->with('alert-success', 'Experience details updated successfully!');
    }

    public function otherDetailsEdit($candidateID)
    {

        $candidate=CandidateUser::findOrFail($candidateID);
        //$candidateCV=CandidateCV::where('candidate_user_id',$candidateID)->latest()->first();
		$candidateCV=CandidateDocument::where('document_type','cv')->where('candidate_user_id',$candidateID)->latest()->first();
        return view('candidate.otherdetails_edit',compact('candidate','candidateCV'));
    }

    public function otherDetailsUpdate(Request $request)
    {
        $request->validate([
            'captcha' => ['required', 'captcha']
            ], [
                'captcha.captcha' => 'The CAPTCHA is incorrect. Please try again.',
        ]);
        
        $candidateID = Auth::user()->id;

        // Update CandidateUser details
        $candidate =CandidateUser::findOrFail($candidateID);
        $candidate->current_ctc = $request->input('current_ctc');
        $candidate->expected_ctc = $request->input('expected_ctc');
        $candidate->tags = $request->input('tags');
        $candidate->portal_ref = $request->input('portal_ref');
        $candidate->save();

        // Handle CV Upload
        if ($request->hasFile('cv_upload')) {
            // Delete existing CV if it exists
            if ($candidate->candidateCV) {
                Storage::delete($candidate->candidateCV->cv_upload);
                $candidate->candidateCV->delete();
            }

            // Upload new CV
            $cvPath = $request->file('cv_upload')->store('cv', 'public');

            // Create new CandidateCV record
            $cv = new CandidateDocument();
            $cv->candidate_user_id = $candidateID;
			$cv->document_type='cv';
            $cv->file_path = $cvPath;
            $cv->save();

            return redirect()->route('myprofile')->with('alert-success', 'Other details updated successfully!');
        }
    }
}
