<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CandidateUser;
use App\Models\CandidateCV;
use App\Models\CandidateDocument;
use Auth;

class MyDocumentController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;

        $candidateDocument = CandidateDocument::where('candidate_user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('document_type'); // Ensure proper method chaining

        return view('candidate.mydocument.index', compact('candidateDocument'));
    }
}
