@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header bg-danger text-light">
                <p>Project : <span>{{$project->title}}</span></p>
                <div class="d-flex justify-content-between">
                    <p>Support Mail Id : <span>{{$project->support_mail_id}}</span></p>
                    <p>Recruter : <span>{{$project->recruiter_assigned}}</span></p>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Location</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requirements as $require)
                            <tr>
                                <td>{{$require->position}}</td>
                                <td>{{$require->locations}}</td>
                                <td>{{ \Carbon\Carbon::parse($require->start_date_time)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($require->expiry_date_time)->format('d-m-Y') }}</td>
                                <td>
                                    @if ($require->status == 1)
                                        Active
                                    @else
                                        Close
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('/contractuals/requirements', [$require->id, $require->position]) }}" class="btn btn-sm btn-danger">Apply</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No Data Found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('jobOpenings') }}" class="btn btn-sm btn-secondary">Back</a>
            </div>
        </div>
    </div>
@endsection