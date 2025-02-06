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
                            <table class="table table-bordered table-light">
                                <tr class="table-dark">
                                    <td colspan="4"><h5 class="text-white">Personal Information <a href="{{ route('application.personal_details', ['requirement_id' => $applicationPersonal->requirement_id, 'position' => $applicationPersonal->post_applied_for]) }}" class="btn btn-sm btn-danger">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    </h5></td>
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
                            <table class="table table-bordered table-light">
                                <tr class="table-dark">
                                    <td colspan="5"><h5 class="text-white">Educational Details <a href="{{route('application.education',$applicationPersonal->id)}}" class="btn btn-sm btn-danger"><i class="fas fa-edit"></i> Edit</a></h5> </td>
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

                            <table class="table table-bordered table-light">
                                <tr class="table-dark">
                                    <td colspan="8"><h5 class="text-white">Certification Details <a href="{{route('application.education',$applicationPersonal->id)}}" class="btn btn-sm btn-danger"><i class="fas fa-edit"></i> Edit</a></h5> </td>
                                </tr>
                                <tr>
                                <tr>
                                    <td><strong>Certification No.</strong></td>
                                    <td><strong>Course</strong></td>
                                    <td><strong>Subject</strong></td>
                                    <td><strong>Percentage</strong></td>
                                    <td><strong>Passing year</strong></td>
                                    <td><strong>Institute</strong></td>
                                    <td><strong>Duration</strong></td>
                                    <td><strong>Mode</strong></td>
                                </tr>
                                    </tr>
                                 @foreach ($applicationCertification as $certificate)
                                    
                                    <tr>
                                        <td>{{ $certificate->certification_number }}</td>
                                        <td>{{ $certificate->course }}</td>
                                        <td>{{ $certificate->subject }}</td>
                                        <td>{{ $certificate->percentage }}</td>
                                        <td>{{ $certificate->passing_year }}</td>
                                        <td>{{ $certificate->institute }}</td>
                                        <td>{{ $certificate->duration }}</td>
                                        <td>{{ $certificate->mode }}</td>
                                    </tr>
                                      
                                @endforeach
                            </table>

                            <table class="table table-bordered table-light">
                                <tr class="table-dark">
                                    <td colspan="7"><h5 class="text-white">Experience Details <a href="{{route('application.experience',$applicationPersonal->id)}}" class="btn btn-sm btn-danger"><i class="fas fa-edit"></i> Edit</a></h5></td>
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
                            <table class="table table-bordered table-light">
                                <tr class="table-dark">
                                    <td colspan="2"><h5 class="text-white">Uploaded Documents <a href="{{route('application.upload',$applicationPersonal->id)}}" class="btn btn-sm btn-danger"><i class="fas fa-edit"></i> Edit</a></h5></td>
                                </tr>
                                <tr>
                                    <td><strong>Document Type:</strong></td>
                                    <td><strong>Document:</strong></td>                           
                                </tr>
                                @foreach ($applicationDocument as $upload)
                                
                                <tr>
                                    <td>{{ ucwords($upload->document_type) }}</td>
                                    <td><a href="{{ asset('storage/' . $upload->document_file) }}" target="_blank">View</a></td>
                                     
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        
                    </div>
                    

                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6 text-end">
                               <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa-solid fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="col-6">
                               
                                <button type="submit" name="" class="btn btn-danger " data-bs-toggle="modal" data-bs-target="#confirmModal"><i class="fas fa-save"></i> Final Submit</button>
                            
                            </div>
                               
                            </div>
                       
                </div>
                        
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade " id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirm Submission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {!!$project->candidate_undertaking!!}
       
      </div>
      <div class="modal-footer">
         <div class="form-check mt-1 me-auto">
    <input class="form-check-input" type="checkbox" id="confirmCheckbox" style="width: 20px; height: 20px; cursor: pointer; border:solid 1px red;">
    <label class="form-check-label mt-1" for="confirmCheckbox" style="font-size: 14px; margin-left: 10px; cursor: pointer;">
        <b>I confirm that the information provided is correct and I wish to proceed with the submission.</b>
    </label>
</div>

        <form action="{{route('application.finalsubmit',$applicationPersonal->id)}}" method="post">
            @csrf
            
            <button type="submit" class="btn btn-danger" id="confirmSubmitButton" disabled><i class="fa-solid fa-angles-right"></i> Proceed</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
@section('script')
<script>
 

  $(document).ready(function() {
    // Function to toggle the submit button's disabled state
    function toggleSubmitButton() {
      $('#confirmSubmitButton').prop('disabled', !$('#confirmCheckbox').is(':checked'));
    }

    $('#confirmCheckbox').on('change', toggleSubmitButton);
    $('#confirmSubmitButton').on('click', function() {
      if ($('#confirmCheckbox').is(':checked')) {
        $('#form').submit();
      } else {
        alert('Please confirm that the information is correct.');
      }
    });
  });
</script>

@endsection