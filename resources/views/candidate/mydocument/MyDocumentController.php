<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CandidateUser;
use App\Models\CandidateCV;
use App\Models\candidateDocument;
use Auth;

class MyDocumentController extends Controller
{
    public function index()
    {
        $userId=Auth::User()->id;
        //$candidateUser=CandidateUser::findOrFail($userId);
        //$candidateCV=CandidateCV::where('candidate_user_id',$userId)->get();
        $candidateDocument = CandidateDocument::where('candidate_user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get()
        ->unique('document_type');`

        return view('candidate.mydocument.index',compact('candidateDocument'));
    }
}
