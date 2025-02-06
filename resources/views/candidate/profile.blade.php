@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
             <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }} alert-dismissible fade show">{{ Session::get('alert-' . $msg) }} 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </p>
               
                @endif
                @endforeach
            </div>
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4>My Profile</h4>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-12">
                               
                                    <div class="card">
                                        <div class="card-header p-1">
                                            <div class="row">
                                                <div class="col-10"><h5 class="text-danger mt-2">Personal Details</h5></div>
                                                <div class="col-2 text-end">
                                                    <a href="{{route('myprofile.personal_details.edit',$candidateUser->id)}}" class="btn btn-sm btn-outline-danger"><i class="fas fa-edit"></i> Edit</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="row">
                                             <!-- <div class="col-md-3">
                                                @php
                                                    $photoDisplayed = false;
                                                    $signatureDisplayed = false;
                                                @endphp

                                                @foreach($candidateDocuments as $candidateDocument)
                                                    @if($candidateDocument->document_type == 'photo' && !$photoDisplayed)
                                                        <img src="{{ asset('storage/' . $candidateDocument->file_path) }}" class="img-thumbnail" style="width: 100%; height: 180px;">
                                                        @php $photoDisplayed = true; @endphp
                                                    @endif
                                                    @if($candidateDocument->document_type == 'signature' && !$signatureDisplayed)
                                                        <img src="{{ asset('storage/' . $candidateDocument->file_path) }}" class="img-thumbnail" style="width: 100%; height: 80px;">
                                                        @php $signatureDisplayed = true; @endphp
                                                    @endif
                                                @endforeach

                                                @if(!$photoDisplayed)
                                                    <img src="{{ asset('img/photo.jpg') }}" style="width: 100%; height: 180px;">
                                                @endif

                                                @if(!$signatureDisplayed)
                                                    <img src="{{ asset('img/sig.jpg') }}" style="width: 100%; height: 80px;">
                                                @endif

                                            </div> -->
                                         <div class="col-md-12">
                                            <table class="table table-sm table-bordered">
                                               
                                                <tr>
                                                    <th>Name: </th>
                                                    <td>{{$candidateUser->name}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Gender: </th>
                                                    <td>{{$candidateUser->gender}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email: </th>
                                                    <td>{{$candidateUser->email}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Mobile: </th>
                                                    <td>{{$candidateUser->mobile}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Present Address: </th>
                                                    <td>{{$candidateUser->present_address}}, {{$candidateUser->present_district}}, {{$candidateUser->present_state}}, {{$candidateUser->present_pincode}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Permanent Address: </th>
                                                    <td>{{$candidateUser->permanent_address}}, {{$candidateUser->permanent_district}}, {{$candidateUser->permanent_state}}, {{$candidateUser->permanent_pincode}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Date of Birth: </th>
                                                    <td>{{ \Carbon\Carbon::parse($candidateUser->date_of_birth)->format('d/m/Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Industry: </th>
                                                    <td>{{$candidateUser->industry}}</td>
                                                </tr>
                                                
                                            </table>
                                            <table class="table table-sm table-striped table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Languages</th>
                                                    <th>Skills</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($candidateLanguages as $language)
                                            <tr>
                                                <td>{{ $language->language }}</td>
                                                <td>
                                                     <div class="row text-center pt-2">
                                                        <div class="col">
                                                            <label><input type="checkbox" disabled {{ $language->read ? 'checked' : '' }}> Read</label>
                                                        </div>
                                                        <div class="col border-start">
                                                            <label><input type="checkbox" disabled {{ $language->write ? 'checked' : '' }}> Write</label>
                                                        </div>
                                                        <div class="col border-start">
                                                            <label><input type="checkbox" disabled {{ $language->speak ? 'checked' : '' }}> Speak</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            @endforeach
                                        </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header p-1">
                                            <div class="row">
                                                <div class="col-10"><h5 class="text-danger mt-2">Education Qualification</h5></div>
                                                <div class="col-2 text-right d-flex justify-content-end align-items-center">
                                                    <a href="{{route('myprofile.education.edit',$candidateUser->id)}}" class="btn btn-sm btn-outline-danger"><i class="fas fa-edit"></i> Edit</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-sm table-bordered">
                                                <tr>
                                                    <th>Stream</th>
                                                    <th>Specialisation</th>
                                                    <th>Passing Year</th>
                                                    <th>University/Board/Institution</th>
                                                    <th>Marks Obtained</th>
                                                    <th>Grade/ % /CGPA</th>
                                                    <th>Mode</th>
                                                </tr>
                                                @foreach($candidateQualification as $qualification)
                                                <tr>
                                                    <td>{{$qualification->stream}}</td>
                                                    <td>{{$qualification->specialisation}}</td>
                                                    <td>{{$qualification->passing_year}}</td>
                                                    <td>{{$qualification->institution}}</td>
                                                    <td>{{$qualification->marks_obtained}}</td>
                                                    <td>{{$qualification->grade}}</td>
                                                    <td>{{$qualification->mode}}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header p-1">
                                            <div class="row">
                                                <div class="col-10"><h5 class="text-danger mt-2">Work Exeperience</h5></div>
                                                <div class="col-2 text-right d-flex justify-content-end align-items-center">
                                                    <a href="{{route('myprofile.experience.edit',$candidateUser->id)}}" class="btn btn-sm btn-outline-danger"><i class="fas fa-edit"></i> Edit</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-sm table-bordered">
                                                <tr>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Company</th>
                                                    <th>Designation</th>
                                                </tr>
                                                @foreach($candidateExperience as $experience)
                                                <tr>
                                                    <td>{{$experience->from_date}}</td>
                                                    <td>{{$experience->to_date}}</td>
                                                    <td>{{$experience->company}}</td>
                                                    <td>{{$experience->designation}}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header p-1">
                                            <div class="row">
                                                <div class="col-10"><h5 class="text-danger mt-2">Other Details</h5></div>
                                                <div class="col-2 text-right d-flex justify-content-end align-items-center">
                                                    <a href="{{route('myprofile.otherdetails.edit',$candidateUser->id)}}" class="btn btn-sm btn-outline-danger"><i class="fas fa-edit"></i> Edit</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-sm table-bordered">
                                               <tr>
                                                   <th>Current CTC</th>
                                                   <td>{{$candidateUser->current_ctc}}</td>
                                               </tr>
                                               <tr>
                                                   <th>Expected CTC</th>
                                                   <td>{{$candidateUser->expected_ctc}}</td>
                                               </tr>
                                               <tr>
                                                   <th>Tags/Keywords/Key Skills</th>
                                                   <td>{{$candidateUser->tags}}</td>
                                               </tr>
                                               <tr>
                                                   <th>Job Portal Reference</th>
                                                   <td>{{$candidateUser->portal_ref}}</td>
                                               </tr>
                                               <tr>
                                                   <th>Your CV</th>
                                                   <td>
                                                    @foreach($candidateDocuments as $candidateDocument)
                                                    @if($candidateDocument->document_type=='cv')
                                                    <a href="{{asset('storage/'.$candidateDocument->file_path)}}" class="btn btn-sm btn-danger">Dowunload your CV <i class="fas fa-download"></i></a> <span class="text-muted">Last updated at {{ \Carbon\Carbon::parse($candidateDocument->created_at)->format('d/m/Y') }}</span>
                                                    @endif
                                                    @endforeach
                                                    </td>
                                               </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="card-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa-solid fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
