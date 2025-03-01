<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

use App\Models\test;
use PhpOffice\PhpWord\IOFactory;
use PHPHtmlParser\Dom;


class HomeController extends Controller
{
    
    public function index()
    {
		
        return view('home');
    }

    public function refresh()
    {
         return response()->json(['captcha' => captcha_img()]);
    }

    public function jobOpenings(){
        $currentDate = \Carbon\Carbon::now();
        $projects = Project::with([
            'client',
            'projectLocations',
            'requirements' => function ($query) use ($currentDate) {
                $query->where('website_publish_date', '<=', $currentDate)->where('status',1);
            },
            'projectNotifications'
        ])
        // ->where('status', 1)
        ->orderBy('start_date', 'desc')
        ->paginate(21);
            // return $projects;
        return view('candidate.jobs.openings',compact('projects')); 
    }
}
