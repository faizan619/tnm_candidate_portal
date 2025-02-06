@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 m-auto">
        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                @endif
            @endforeach
        </div>

        <div class="row mb-2">
            <div class="col-md-8 m-auto">
                <form action="{{ route('filterProjects') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-9 m-auto">
                            <div class="btn-group">
                                <select name="job_type" class="form-select form-control-sm">
                                    <option selected="" disabled="">Select Job Type</option>
                                    <option value="Permanent">Permanent</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Contract-to-hire">Contract-to-hire</option>
                                    <option value="Direct basis">Direct basis</option>
                                    <option value="Job basis">Job basis</option>
                                    <option value="Outsourcing basis">Outsourcing basis</option>
                                    <option value="Consultant">Consultant</option>
                                </select>
                                <select name="client_name" class="form-select form-control-sm">
                                    <option selected="" disabled="">Select Client</option>
                                    @php
                                        $clientNames = [];
                                    @endphp
                                    @foreach($filters as $filter)
                                        @php
                                            $clientName = $filter->client->short_name;
                                        @endphp
                                        @if (!in_array($clientName, $clientNames))
                                            <option value="{{ $clientName }}">{{ $clientName }}</option>
                                            @php
                                                $clientNames[] = $clientName;
                                            @endphp
                                        @endif
                                    @endforeach
                                </select>
                                <select name="project_title" class="form-select form-control-sm">
                                    <option selected="" disabled="">Select Project</option>
                                    @foreach($filters as $filter)
                                        <option value="{{ $filter->title }}">{{ $filter->title }}</option>
                                    @endforeach
                                </select>
                                <input type="submit" value="Search" class="btn btn-danger">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark">
                <div class="row">
                    <div class="col-6 text-white">
                        Requirements
                    </div>
                </div>
            </div>
            <div class="card-body bg-white">
                <div class="row">
                    <div class="col-12 mb-2">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="projectsTable" class="table table-sm table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Project Ref#</th>
                                <th>Project Title</th>
                                <th style="width:120px">Positions</th>
                                <th style="width:150px; white-space: nowrap;">View & Apply</th>
                                <th>Notification</th>
                                <th>Locations</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                @if($project && $project->requirements->isNotEmpty()) {{-- Check if project and requirements exist --}}
                                    @foreach($project->requirements as $index => $requirement)
                                        <tr>
                                            @if($index === 0)
                                                @if($project)
                                                    <td rowspan="{{ $project->requirements->count() }}">{{ $project->client->short_name ?? 'N/A' }}</td>
                                                    <td rowspan="{{ $project->requirements->count() }}">
                                                        {{ $project->title }}
                                                        @if($project->description)
                                                            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $project->id }}">
                                                                Read more
                                                            </button>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td colspan="8">Project data is missing.</td>
                                                @endif
                                            @endif
                                            <td>
                                                <div class="row">
                                                    <div class="col">
                                                        {{ $requirement->position }} [{{ $requirement->no_of_vacancies }}]
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ url('/contractuals/requirements', [$requirement->id, $requirement->position]) }}" class="mb-1">View & Apply</a>
                                            </td>
                                           <td>
                                               <button type="button" class="btn btn-sm btn-danger notification-link" data-bs-toggle="modal" data-bs-target="#modalNotification" onclick="loadNotifications({{ $requirement->project_id }})" title="Notification">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                            </td>
                                            @php
                                                // Include state abbreviations file
                                                $stateAbbreviations = include(resource_path('views/contractuals/state_abbreviations.php'));

                                                // Check if locations is not null and is a valid JSON string
                                                $locations = !empty($requirement->locations) && is_string($requirement->locations)
                                                                ? json_decode($requirement->locations, true)
                                                                : [];

                                                // Only process if locations array is not empty
                                                if (!empty($locations)) {
                                                    // Replace full state names with abbreviations
                                                    $abbreviatedLocations = array_map(function($location) use ($stateAbbreviations) {
                                                        // Separate parts by commas (this handles city and state)
                                                        $parts = explode(',', $location);

                                                        // The last part should be the state name, we will replace it with abbreviation
                                                        $state = trim(array_pop($parts)); // Get the state part and remove it from the array
                                                        $normalizedState = ucwords(strtolower($state)); // Normalize state for comparison

                                                        // Check if the normalized state exists in the abbreviations
                                                        if (array_key_exists($normalizedState, $stateAbbreviations)) {
                                                            $state = $stateAbbreviations[$normalizedState]; // Replace full state with abbreviation
                                                        }

                                                        // Reassemble the location string (joining city parts back with state)
                                                        $fullLocation = implode(', ', $parts) . ', ' . $state;

                                                        return $fullLocation;
                                                    }, $locations);
                                                } else {
                                                    $abbreviatedLocations = [];
                                                }
                                            @endphp

                                            <td style="min-width:150px">
                                                @foreach ($abbreviatedLocations as $location)
                                                    {{ $location }}<br>
                                                @endforeach
                                            </td>

                                            <td>{{ \Carbon\Carbon::parse($requirement->start_date_time)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($requirement->expiry_date_time)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($requirement->status==1) 
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($requirement->status==2)
                                                    <span class="badge bg-warning">On hold</span>
                                                @elseif($requirement->status==3)
                                                    <span class="badge bg-danger">Closed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                               
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $projects->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
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

<!-- Modal for Notifications -->
<div class="modal fade" id="modalNotification" tabindex="-1" aria-labelledby="modalNotificationLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNotificationLabel">Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Notifications will load here -->
                <div id="notificationsContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
   function loadNotifications(projectId) {
        $.ajax({
            url: `project-notifications/${projectId}`,
            method: 'GET',
            success: function(notifications) {
                const notificationBody = $('#notificationBody');
                notificationBody.empty(); // Clear previous notifications

                if (notifications.length === 0) {
                    notificationBody.append('<p>No notifications available.</p>');
                    return;
                }

                notifications.forEach(notification => {
                    const date = new Date(notification.date);
                    const formattedDate = `${('0' + date.getDate()).slice(-2)}/${('0' + (date.getMonth() + 1)).slice(-2)}/${date.getFullYear()}`;
                    
                    const notificationHTML = `
                        <div class="notification">
                            <h5>${notification.title}</h5>
                            <p>${formattedDate}</p>
                            <a href="${notification.document_upload}" target="_blank">View Document</a>
                        </div>
                        <hr>
                    `;
                    notificationBody.append(notificationHTML);
                });
            },
            error: function() {
                $('#notificationBody').append('<p>Error loading notifications.</p>');
            }
        });
    }

    $(document).ready(function() {

        $('#searchInput').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#projectsTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        
    });

</script>
@endsection
