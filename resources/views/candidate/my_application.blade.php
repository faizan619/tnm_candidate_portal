@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h3 class="text-muted"><b>{{ __('My Applications') }}</b></h3>
                
                <div class="bg-white p-4">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('myapplication') }}" class="mb-3" id="filterForm">
                    <div class="row">
                        <div class="col-md-12">
                            <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                <option value="">Submitted Applications</option>
                                <option value="Complete" {{ request('status') == 'Complete' ? 'selected' : '' }}>Complete Applications</option>
                                <option value="Incomplete" {{ request('status') == 'Incomplete' ? 'selected' : '' }}>Incomplete Applications</option>
                            </select>
                        </div>
                    </div>
                </form>
                
                <div class="row">
                    @foreach($applications as $app)
                    <div class="col-md-4 mb-2"> 
                        <div class="card bg-white">
                            <div class="card-body ps-0 pe-0">
                                <p class="ps-2 pe-2 text-danger" style="background:#fff4f6; font-weight: bold">{{$app->requirement->project->title ?? 'N/A'}}</p>
                                <p class="ps-2 pe-2">Application Ref: #{{$app->id}}</p>
                                <p class="ps-2 pe-2">Project Ref: #{{ $app->requirement->project->project_ref ?? 'N/A' }}</p>
                                <p class="ps-2 pe-2">Position: <span class="badge bg-danger">{{$app->post_applied_for}}</span></p>
                                <p class="ps-2 pe-2">Client Name: {{$app->requirement->project->client->name ?? 'N/A'}}</p>
                                <p class="ps-2 pe-2">Applied Date: {{ \Carbon\Carbon::parse($app->updated_at)->format('d/m/Y H:i:s') }}</p>
                                <p class="bg-light ps-2 pe-2">Status: <span class="
                                        
                                        @if($app->status == 'Complete') text-warning 
                                        @elseif($app->status == 'Submitted') text-success 
                                        @elseif($app->status == 'Incomplete') text-danger 
                                        @else text-danger 
                                        @endif
                                    ">
                                        <b>
                                            @if($app->status == 'Complete')
                                                Complete but not submitted
                                            @else
                                                {{$app->status}}
                                            @endif
                                        </b>
                                    </span></p>
                                <div class="p-2">
                                    @if($app->status == 'Complete')
                                        <a href="{{route('application.preview', $app->id)}}" class="btn btn-warning text-white w-100">Submit</a>
                                    @elseif($app->status=='Incomplete')
                                         <a href="{{route('application.preview', $app->id)}}" class="btn btn-danger w-100">Complete</a>
                                    @else
                                    <div class="btn-group w-100">
                                        
                                            <a href="{{route('myapplication.details', $app->id)}}" class="btn btn-success">View Details</a>
                                            <a href="{{route('application.download',$app->id)}}" class="btn btn-outline-success"><i class="fas fa-download"></i></a>
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
