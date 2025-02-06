@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4>Application Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Position:</strong>
                            {{ $applicationPersonal->post_applied_for }}
                        </div>
                        <div class="col-md-6">

                            <strong>Location:</strong>
                            {{ $applicationPersonal->locations }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"><h5 class="text-danger">Personal Information</h5></td>
                                </tr>
                                  <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $applicationPersonal->name }}</td>
                                    <th>ID Proof</th>
                                    <td>{{ $applicationPersonal->id_proof_type }}: {{ $applicationPersonal->id_proof_no }}</td>
                                </tr>
                                <tr>
                                    
                                </tr>
                                <tr>
                                    <td><strong>Caste:</strong></td>
                                    <td>{{ $applicationPersonal->caste }}</td>
                                    <td><strong>Date of Birth:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($applicationPersonal->date_of_birth)->format('d/m/Y') }}</td>
                                </tr>
                               
                                <tr>
                                    <td><strong>Place of Birth:</strong></td>
                                    <td>{{ $applicationPersonal->place_of_birth }}</td>
                                    <td><strong>Gender:</strong></td>
                                    <td>{{ $applicationPersonal->gender }}</td>
                                </tr>
                                
                                 <tr>
                                    <td><strong>Physically Challenged:</strong></td>
                                    <td>
                                        {{ $applicationPersonal->physically_challenged }},
                                        @if($applicationPersonal->physically_challenged=='Yes')
                                            {{$applicationPersonal->percentage_of_disability}} % of Disability
                                        @endif
                                    </td>
                                    <td><strong>Religion:</strong></td>
                                    <td>{{ $applicationPersonal->religion }}</td>
                                </tr>
                               
                                <tr>
                                    <td><strong>Present Address:</strong></td>
                                    <td>{{ $applicationPersonal->present_address }}, {{ $applicationPersonal->present_district }}, {{ $applicationPersonal->present_state }}, {{ $applicationPersonal->present_pincode }}
                                    </td>
                                     <td><strong>Present District:</strong></td>
                                    <td>{{ $applicationPersonal->permanent_address }}, {{ $applicationPersonal->present_district }},   {{ $applicationPersonal->permanent_state }}, {{ $applicationPersonal->permanent_pincode }}</td>
                                </tr>
                                <tr>
                                   
                                    <td><strong>Marital Status:</strong></td>
                                    <td>{{ $applicationPersonal->marital_status }}</td>
                                    <td><strong>Spouse Name</strong></td>
                                    <td>{{ $applicationPersonal->spouse_name }}</td>
                                </tr>
                                 <tr>
                                    <td><strong>Father's Name:</strong></td>
                                    <td>{{ $applicationPersonal->father_name }}</td>
                                    <td><strong>Mother's Name:</strong></td>
                                    <td>{{ $applicationPersonal->mother_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mobile:</strong></td>
                                    <td>{{ $applicationPersonal->mobile }}</td>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $applicationPersonal->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>{{ $applicationPersonal->status }}</td>
                                    <td><strong>Applied Date:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($applicationPersonal->created_at)->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"><h5 class="text-danger">Educational Details</h5></td>
                                </tr>
                                <tr>
                                        <td><strong>Stream:</strong></td>
                                        <td><strong>Year of Passing:</strong></td>
                                        <td><strong>University/Board/Institution:</strong></td>
                                        <td><strong>Grade / % / CGPA:</strong></td>
                                       <td><strong>Mode:</strong></td>
                                    </tr>
                                 @foreach ($applicationEducation as $education)
                                    
                                    <tr>
                                        <td>{{ $education->stream }}</td>
                                        <td>{{ $education->year_of_passing }}</td>
                                        <td>{{ $education->university_institutions}}</td>
                                        <td>{{ $education->grade}}</td>
                                        <td>{{ $education->mode}}</td>
                                    </tr>
                                      
                                @endforeach
                            </table>
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="2"><h5 class="text-danger">Experience Details</h5></td>
                                </tr>
                                <tr>
                                    <td><strong>From:</strong></td>
                                    <td><strong>To:</strong></td>
                                    <td><strong>Company:</strong></td>
                                    <td><strong>Designation:</strong></td>
                                    <td><strong>Work Type:</strong></td>
                                    <td><strong>CTC:</strong></td>
                                    <td><strong>Responsibility:</strong></td>
                                </tr>
                                @foreach ($applicationExperience as $experience)
                                <tr>
                                    
                                    <td>{{ $experience->from_date }}</td>
                                    <td>{{ $experience->to_date }}</td>
                                    <td>{{ $experience->company }}</td>
                                    <td>{{ $experience->designation }}</td>
                                    <td>{{ $experience->work_type }}</td>
                                    <td>{{ $experience->ctc }}</td>
                                    <td>{{ $experience->responsiblity }}</td>
                                </tr>
                                
                                @endforeach
                            </table>
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="2"><h5 class="text-danger">Uploaded Documents</h5></td>
                                </tr>
                                <tr>
                                    <td><strong>Document Type:</strong></td>
                                    <td><strong>Document:</strong></td>                           
                                </tr>
                                @foreach ($applicationDocument as $upload)
                                
                                <tr>
                                    <td>{{ $upload->document_type }}</td>
                                    <td><a href="{{ asset('storage/' . $upload->document_file) }}" download>Download</a></td>
                                     
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        
                    </div>
                    

                </div>
                <div class="card-footer">
                    <a href="{{route('myapplication')}}" class="btn btn-danger"><i class="fa-solid fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
