<?php

namespace App\Http\Controllers;

use App\Models\ClientHo;
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

    public function jobOpenings(Request $request)
    {


        $filters = Project::with(['client' => function ($query) {
            $query->select('id', 'short_name');
        }])
            ->select('id', 'client_id', 'title')
            ->where('status', 1)
            ->orderBy('start_date', 'desc')
            ->get();
        $projectNames = Project::select('title', 'id')->get();
        $currentDate = \Carbon\Carbon::now();
        $projects = Project::with([
            'client',
            'projectLocations',
            'requirements' => function ($query) use ($currentDate) {
                $query->where('website_publish_date', '<=', $currentDate)->where('status', 1);
            },
            'projectNotifications'
        ])
            ->where('status', 1)
            ->orderBy('start_date', 'desc')
            ->paginate(21);
        return view('candidate.jobs.openings', compact('projectNames', 'projects', 'filters'));
    }

    public function FilterJobOpenings(Request $request)
    {
        // return $request;
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
        $clients = $clientsQuery->get();
        $filters = Project::with(['client' => function ($query) {
            $query->select('id', 'short_name');
        }])
            ->select('id', 'client_id', 'title')
            ->where('status', 1)
            ->orderBy('start_date', 'desc')
            ->get();
        return view('candidate.jobs.openings', compact('projects', 'filters'));
    }
}
