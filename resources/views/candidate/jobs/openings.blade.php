<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{asset('img/logo.jpg')}}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <title>Job Openings</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .pagestyle {
            background-color: #E6E6E6;
        }

        .upperjob {
            background-color: #212884;
            color: white;
            height: 250px;
        }

        @media (max-width:480px) {
            .banner-select {
                max-width: 25rem;
            }

            .col-sm-13 {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <div class="pagestyle" style="min-height: 100vh;">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand p-0" href="{{ url('/') }}">
                    <img src="{{asset('img/logo.jpg')}}" alt="T&M Logo" width="120">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="{{route('home')}}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('current_opening')}}">Current Opening</a></li>
                        <!-- <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Current Openings
                          </a>
                          <ul class="dropdown-menu bg-white" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{url('contractuals')}}">Contractual</a></li>
                            <li><a class="dropdown-item" href="#">Permanent</a></li>
                            
                          </ul>
                        </li> -->

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>

                        @endif
                        @else

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fa-solid fa-circle-user" style="font-size: 40px; float: left;"></i>
                                <span style="line-height: 40px; margin-left: 5px;">
                                    {{ucwords(Auth::user()->name) }}
                                </span>
                            </a>

                            <div class="dropdown-menu bg-white dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{route('myprofile')}}">
                                    My profile
                                </a>
                                <a class="dropdown-item" href="{{route('myapplication')}}">
                                    My applications
                                </a>
                                <a class="dropdown-item" href="{{route('mydocument')}}">
                                    My documents
                                </a>
                                <a class="dropdown-item" href="{{route('apply_for_job')}}">
                                    Apply for a job
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="">Need help? Raise a ticket</a>
                                <a class="dropdown-item" href="{{ route('password.change') }}">
                                    Change Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div>
            <div class="upperjob p-3 d-flex justify-content-center align-items-center flex-column">
                <div class="text-center">
                    <h2 class="fw-bold mb-3">T&M Job Board</h2>
                </div>
                <form action="{{ route('FilterJobOpenings') }}" method="post" style="width: 100%;" class="d-flex flex-wrap gap-3 justify-content-center"> {{-- Change from POST to GET --}}
                    @csrf
                    <div class="col-md-4 col-sm-13">
                        <select name="client_name" class="form-select banner-select form-control-sm p-3">
                            <option selected disabled>Select Client</option>
                            @php $clientNames = []; @endphp
                            @foreach($filters as $filter)
                            @php $clientName = $filter->client->short_name; @endphp
                            @if (!in_array($clientName, $clientNames))
                            <option value="{{ $clientName }}" {{ request('client_name') == $clientName ? 'selected' : '' }}>
                                {{ $clientName }}
                            </option>
                            @php $clientNames[] = $clientName; @endphp
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="project_title" class="form-select form-control-sm p-3">
                            <option selected disabled>Select Project</option>
                            @foreach($filters as $filter)
                            <option value="{{ $filter->title }}" {{ request('project_title') == $filter->title ? 'selected' : '' }}>
                                {{ $filter->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 btn-group">
                        <button type="submit" class="btn btn-danger">Search</button>&nbsp;
                        <a href="{{ route('jobOpenings') }}" class="btn btn-secondary d-flex justify-content-center align-items-center">Reset</a>
                    </div>
                </form>
            </div>
            <div class="lowerjob pt-5">
                <h2 class="text-center fw-bold mb-3">Browse T&M Jobs</h2>
                <div class="container">
                    <div class="row">

                        @foreach ($projects as $project)
                        <div class="col-lg-6">
                            <div class="card my-3 p-3 shadow" style="min-height:220px;">
                                <span>
                                    <div style="width: 80%;display:inline-block">
                                        <h6 style="font-weight:bold"><span>{{$project->title}}</span></h6>
                                    </div>
                                    <p style="font-size: 15px;font-weight:bold;display:inline-block" class="@if($project->status == 1) text-success @else text-danger @endif">Status : @if ($project->status == 1)
                                        Active
                                        @else
                                        Close
                                        @endif
                                    </p>
                                </span>

                                @php
                                $locations = $project->projectLocations;
                                $firstLocation = $locations->first(); // Get the first location
                                $remainingLocations = $locations->slice(1); // Get the rest of the locations
                                @endphp

                                <div class="mb-2">
                                    <span style="display: block;">Ref #: <b>{{$project->project_ref}}</b> </span>
                                    <div style="max-height: 2rem;overflow:auto">
                                        <span class=""><i class="fa-solid fa-location-dot text-danger"></i>&nbsp;Locations : </span>
                                        @if ($firstLocation)
                                            <span class="@if(count($remainingLocations) > 0) @endif" title="">
                                                {{ "{$firstLocation->district}" }}
                                                @if (count($remainingLocations) > 0)
                                                    @foreach ($remainingLocations as $loc)
                                                        <li style="display: inline;">, {{ "{$loc->district}" }}</li>
                                                    @endforeach
                                                @endif
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="d-flex">
                                    <p style="font-size:15px"><i class="fa-solid fa-calendar-days text-danger"></i>&nbsp;Last Date : {{\Carbon\Carbon::parse($project->expiry_date)->format('d-m-Y')}}</p>
                                </div>
                                <div class="mt-auto">
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#positionModal{{ $project->id }}">View Position</button>
                                    @if($project->description)
                                    <button type="button" class="btn btn-link text-secondary" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $project->id }}">
                                        Read more
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
    <footer class="p-4 text-center">
        <p>For Any Inquery Mail At : <a href="mailto:jobs.hr@tnmhr.com">jobs.hr@tnmhr.com</a></p>
        <hr>
        <p class="mb-0">Copy Right &copy; 2024, All rights reserved by <a href="tnmhr.com" class="text-danger" style="text-decoration: none;">tnmhr.com</a></p>
    </footer>


    <!-- Modal for Project Description -->
    @foreach($projects as $project)
    @if($project) {{-- Check if the project exists --}}
    <div class="modal fade" id="descriptionModal{{ $project->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $project->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel{{ $project->id }}">Project Description</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! $project->description !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach

    <!-- Modal for Positions -->
    @foreach($projects as $project)
    @if($project) {{-- Check if the project exists --}}
    <div class="modal fade" id="positionModal{{ $project->id }}" tabindex="-1" aria-labelledby="positionModalLabel{{ $project->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="positionModalLabel{{ $project->id }}">Available Positions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Location</th>
                                <!-- <th>Start Date</th> -->
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($project->requirements && count($project->requirements) > 0)
                            @foreach($project->requirements as $requirement)
                            <tr>
                                <td>{{$requirement['position']}}</td>
                                <td>{{$requirement['locations']}}</td>
                                <!-- <td>{{\Carbon\Carbon::parse($requirement['start_date_time'])->format('d-m-Y')}}</td> -->
                                <td>{{\Carbon\Carbon::parse($requirement['expiry_date_time'])->format('d-m-Y')}}</td>
                                <td>@if($requirement['status']==1) Active @else Close @endif</td>
                                <td><a class="btn btn-sm btn-danger" href="{{ url('/contractuals/requirements', [$requirement['id'], $requirement['position']]) }}" class="mb-1">Apply</a></td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6">No Positions Found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</html>