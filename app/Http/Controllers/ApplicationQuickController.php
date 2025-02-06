<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LookupValue;
use App\Models\Requirement;
use App\Models\RequirementQualification;
use App\Models\Project;
use App\Models\ApplicationQuick;
use Carbon\Carbon;


class ApplicationQuickController extends Controller
{
    public function index($requirementId,$position)
    {

         //lookup type for gender is 3
        $genders=LookupValue::where('type_id',3)->get();
        $requirements=Requirement::findOrFail($requirementId);
        $qualifications=RequirementQualification::where('requirement_id',$requirementId)->get();
         //lookup type id for stream is 10
        $streams = LookupValue::where('type_id', 10)
                      ->whereNotIn('value_description', ['Below 10th', 'SSC', 'HSC', 'Diploma'])
                      ->get();
        $projects = Project::select('id', 'application_mode', 'terms_conditions', 'general_instructions_candidate')->findOrFail($requirements->project_id);
        return view('contractuals.application_quick.index',compact('requirements','position','genders','qualifications','streams','projects'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'captcha' => ['required', 'captcha']
            ], [
                'captcha.captcha' => 'The CAPTCHA is incorrect. Please try again.',
        ]);

        

        $cv_file = $request->file('cv_file')->store('cv', 'public');

        $applicationQuick = new ApplicationQuick();
        $applicationQuick->requirement_id = $request->requirement_id;
        $applicationQuick->job_type = $request->job_type;
        $applicationQuick->post_applied_for = $request->position;
        $applicationQuick->location = $request->location;
        $applicationQuick->name = $request->name;
        $applicationQuick->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');;
        $applicationQuick->gender = $request->gender;
        $applicationQuick->father_name = $request->father_name;
        $applicationQuick->mobile = $request->mobile;
        $applicationQuick->email = $request->email;
        $applicationQuick->qualification = $request->qualification;
        $applicationQuick->stream = $request->stream;
        $applicationQuick->address = $request->address;
        $applicationQuick->pincode = $request->present_pincode;
        $applicationQuick->state = $request->present_state;
        $applicationQuick->district = $request->present_district;
        $applicationQuick->cv_file = $cv_file;
        $applicationQuick->save();

       
        return redirect()->route('application.success')->with('success', 'Application submitted successfully.');
    }


}
