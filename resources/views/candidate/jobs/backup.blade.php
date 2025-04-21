@extends('layouts.app')

@section('style')
<style>
    /* Tooltip container (Location Badge) */
.tooltip1 {
    position: relative; /* Make it the reference for absolute positioning */
    display: inline-block;
    cursor: pointer;
}

/* Tooltip text */
.tooltip1 .tooltip1text {
    visibility: hidden;
    width: 320px;
    background-color: #555;
    color: #fff;
    text-align: left;
    padding: 8px;
    border-radius: 6px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);

    /* Positioning directly below the badge */
    position: absolute;
    z-index: 10;
    top: 100%;  /* Position below the badge */
    left: 50%;  /* Align with the center */
    transform: translateX(-50%); /* Ensure it's centered */
    margin-top: 5px; /* Small gap between badge and tooltip */

    /* Fade-in effect */
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

/* Tooltip arrow */
.tooltip1 .tooltip1text::after {
    content: "";
    position: absolute;
    top: -5px; /* Moves arrow to the top of the tooltip */
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: transparent transparent #555 transparent; /* Arrow pointing upwards */
}

/* Show tooltip when hovering over the badge */
.tooltip1:hover .tooltip1text {
    visibility: visible;
    opacity: 1;
}

</style>
@endsection

@section('content')

<div class="row mb-2">
    <div class="col-md-8 m-auto">
        <form action="{{ route('FilterJobOpenings') }}" method="post"> {{-- Change from POST to GET --}}
            @csrf
            <div class="row">
                <div class="col-10 mx-auto d-flex justify-content-between">
                    <select name="client_name" class="form-select form-control-sm">
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
                    </select>&nbsp;

                    <select name="project_title" class="form-select form-control-sm">
                        <option selected disabled>Select Project</option>
                        @foreach($filters as $filter)
                        <option value="{{ $filter->title }}" {{ request('project_title') == $filter->title ? 'selected' : '' }}>
                            {{ $filter->title }}
                        </option>
                        @endforeach
                    </select>&nbsp;

                    <button type="submit" class="btn btn-primary">Search</button>&nbsp;
                    <a href="{{ route('jobOpenings') }}" class="btn btn-secondary">Reset</a>&nbsp;
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @foreach ($projects as $project)
    <div class="col-lg-5 col-xl-4">
        <div class="card my-3 p-3 shadow" style="height:250px;">
            <div class="d-flex justify-content-between">
                <div style="width: 70%;">
                    <h6 style="font-weight:bold">Ref #: <span style="text-decoration: underline;">{{$project->project_ref}}</span> - <span style="text-decoration: underline;">{{$project->title}}</span></h6>
                </div>
                <p style="font-size: 15px;font-weight:bold" class="@if($project->status == 1) text-success @else text-danger @endif">Status : @if ($project->status == 1)
                    Active
                    @else
                    Close
                    @endif
                </p>
            </div>

            @php
            $locations = $project->projectLocations;
            $firstLocation = $locations->first(); // Get the first location
            $remainingLocations = $locations->slice(1); // Get the rest of the locations
            @endphp

            <div class="mb-2">
                <span class="mx-2">Locations : </span>
                @if ($firstLocation)
                <span class="badge bg-secondary p-2 m-1 @if(count($remainingLocations) > 0) tooltip1 @endif" title="">
                    {{ "{$firstLocation->district}" }}

                    @if (count($remainingLocations) > 0)
                        <span class="tooltip1text">
                            <ul>
                                @foreach ($remainingLocations as $loc)
                                <li>{{ "{$loc->district}" }}</li>
                                @endforeach
                            </ul>
                        </span>
                    @endif

                </span>
                @endif
            </div>

            <div class="d-flex">
                <p style="font-size:15px">Last Date : {{\Carbon\Carbon::parse($project->expiry_date)->format('d-m-Y')}}</p>
            </div>
            <div class="mt-auto">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#positionModal{{ $project->id }}">View Position</button>
                @if($project->description)
                <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $project->id }}">
                    Read more
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Add pagination with filter parameters --}}
<div class="d-flex justify-content-center">
    {{ $projects->appends(request()->query())->links() }}
</div>


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



@endsection