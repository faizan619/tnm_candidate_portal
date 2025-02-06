<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationPersonal;
use App\Models\ApplicationEducation;
use App\Models\ApplicationExperience;
use App\Models\ApplicationDocument;
use App\Models\ApplicationCertification;
use Auth;
use PDF;

class MyApplicationController extends Controller
{
    
    public function index(Request $request)
    {
        $query = ApplicationPersonal::query();
         $query->where('candidateuser_id', Auth::user()->id);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Eager load the requirement and project relationships
        $applications = $query->with('requirement.project.client')->get();
        //dd($applications[0]->requirement->project->client->name);
        return view('candidate.my_application', compact('applications'));
    }


    public function myApplication_details($application_id)
    {
         $candidateUserId = Auth::User()->id;
        $applicationPersonal = ApplicationPersonal::where('candidateuser_id', $candidateUserId)->where('id', $application_id)->first();
        $applicationEducation = ApplicationEducation::where('application_id', $application_id)->get();
        $applicationExperience = ApplicationExperience::where('application_id', $application_id)->get();
        $applicationDocument = ApplicationDocument::where('application_id', $application_id)->get();
        
        return view('candidate.my_application_details', compact('applicationPersonal', 'applicationEducation', 'applicationExperience', 'applicationDocument'));
    }

    public function applicationDownload($application_id)
    {

        $candidateUserId = Auth::user()->id;
        $applicationPersonal = ApplicationPersonal::where('candidateuser_id', $candidateUserId)
                                                  ->where('id', $application_id)
                                                  ->first();
        $currentCTC = ApplicationExperience::where('application_id', $application_id)
                                                    ->orderBy('to_date', 'desc')
                                                    ->first();

        $applicationEducation = ApplicationEducation::where('application_id', $application_id)->get();
        $applicationExperience = ApplicationExperience::where('application_id', $application_id)->get();
        $applicationDocument = ApplicationDocument::where('application_id', $application_id)->get();
        $applicationCertification=ApplicationCertification::where('application_id',$application_id)->get();


        // Load the view and pass the data
        $pdf = PDF::loadView('candidate.application_download_pdf', compact('applicationPersonal','currentCTC','applicationEducation','applicationExperience','applicationDocument','applicationCertification'));
        
        return $pdf->download('application_details.pdf');

       //return view('candidate.application_download_pdf', compact('applicationPersonal','currentCTC','applicationEducation','applicationExperience','applicationDocument','applicationCertification'));
    }

}
