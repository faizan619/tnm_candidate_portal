<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Requirement Details</title>
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
</head>
<body>
    <div class="container mb-4">
        <div class="text-center m-4">
            <img src="{{asset('img/logo.jpg')}}" alt="T&M Logo">
        </div>
        <div class="row">
    <div class="col-md-12 m-auto">
         @if(session('success'))
          <div class="alert alert-success">{{ session('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
        @endif

        <div class="card">
            <div class="card-header bg-dark">
                <div class="row">
                    <div class="col-6">
                        Requirement Details
                    </div>
                   
                    <div class="col-6 text-right">
                        
                    </div>
                </div>
            </div>
            <div class="card-body bg-white">
                <p class="text-center"><strong></strong></p>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered table-striped">
                            <tr>
                                <td>Position</td><td>{{$requirements->position}}</td>
                            </tr><tr>
                                <td>Start Date Time</td><td>{{ \Carbon\Carbon::parse($requirements->start_date_time)->format('d/m/Y H:i:s') }}</td>
                            </tr><tr>
                                <td>Expiry Date Time</td><td>{{ \Carbon\Carbon::parse($requirements->expiry_date_time)->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td>Extended Date Time</td><td>{{ \Carbon\Carbon::parse($requirements->extended_date_time)->format('d/m/Y H:i:s') }}</td>
                            </tr>
                           <!--  <tr>
                                <td>Job Title</td><td>{{$requirements->job_title}}</td>
                            </tr> -->
                            <tr>
                                <td>About Position</td><td>{!! $requirements->about_position !!}</td>
                            </tr>
                             <tr>
                                <td>Qualification Description</td><td>{!! $requirements->qualification_description !!}</td>
                            </tr>
                             <tr>
                                <td>Experience Description</td><td>{!! $requirements->experience_description !!}</td>
                            </tr>
                             <tr>
                                <td>Benefit Description</td><td>{!! $requirements->benefit_description !!}</td>
                            </tr>
                            <tr>
                                <td>Locations</td><td>{{$requirements->locations}}</td>
                            </tr><tr>
                                <td>Job Type</td><td>{{$requirements->job_type}}</td>
                            </tr><tr>
                                <td>Job Category</td><td>{{$requirements->job_category}}</td>
                            </tr>
                            
                            <tr>
                                <td>No Of Vacancies</td><td>
                                    {{$requirements->no_of_vacancies}}
                                </td>
                            </tr>
                            <tr>
                                <td>No of Positions Reserved for Women</td><td>
                                    {{$requirements->reserved_for_women}}
                                </td>
                            </tr>
                            <!-- <tr>
                                <td>Total Experience as on Date</td><td>
                                    {{\Carbon\Carbon::parse($requirements->total_experience_date)->format('d/m/Y')}}
                                </td>
                            </tr>
                            <tr>
                                <td>Relevant Experience</td><td>
                                    {{$requirements->relevant_experience}}
                                </td>
                            </tr> -->
                            @if($requirements->salary_display=='Yes')
                            <tr>
                                <td>Salary</td><td>
                                    {{$requirements->salary}}
                                </td>
                            </tr>
                            @endif
                             <tr>
                                <td>Reservation Details</td>
                                <td>
                                   
                                    <table>
                                        <tr>
                                            <th>Caste</th>
                                            <th>Age Limit</th>
                                            <th>Relaxation Limit</th>
                                            <th>Age as on</th>

                                        </tr>
                                        @foreach($age_limits as $age_limit)
                                        <tr>
                                            
                                            <td>{{$age_limit->caste}}</td>
                                            <td>{{$age_limit->age_limit}}</td>
                                            <td>{{$age_limit->relaxation_limit}}</td>
                                            <td>{{$age_limit->age_as_on}}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    
                                        
                                </td>
                            </tr>
                            
                        </table>

                    </div>
                    
                </div>
                <div class="text-center">
                
                    <button class="btn btn-danger mr-4" data-toggle="modal" data-target="#share">Share <i class="fas fa-share"></i></a>
                     <button id="applyButton" class="btn btn-danger" data-application-mode="{{ $projects->application_mode }}"    data-requirement-id="{{ $requirements->id }}" data-position="{{ $requirements->position }}">Apply <i class="fa-solid fa-hand-point-up"></i> </button>
                    
                </div>
            </div>

            <div class="card-footer">
               
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="apply" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Terms & Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3>Terms & Conditions</h3>
        <div class="responsive" 
            {!!$projects->terms_conditions!!}
        </div>

        <h3>General Instructions</h3>
        {!!$projects->general_instructions_candidate!!}

        <h3>Document To Be Uploaded</h3>
        
            <table class="table table-sm table-bordered" style="width:300px">
                <tr>
                    <th>Document Name</th>
                    <th>Type</th>
                    <th>Size</th>
                </tr>
                @foreach($projects_doc as $document)
                <tr>
                    <td>{{$document->document_name}}</td>
                    <td>{{$document->doc_type}}</td>
                    <td>{{$document->file_size}}{{$document->size_type}}</td>

                </tr>
                @endforeach
            </table>
        
      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <div class="col-8 text-left">
            <div class="form-check text-danger">
              <input type="checkbox" class="form-check-input" id="acceptTerms">
              <label class="form-check-label" for="acceptTerms">I accept the terms and conditions</label>
            </div>
          </div>
          <div class="col-4">
           <button onclick="window.location='{{ route('application.personal_details', ['requirement_id' => $requirements->id, 'position' => $requirements->position]) }}'" class="btn btn-danger float-end" id="saveChangesBtn" disabled>Apply</button>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="share" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Share</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Share options -->
        <form action="{{url('contractuals/share/email') }}" id="form" method="post">
            @csrf
            <input type="hidden" name="url" value="{{ url('/contractuals/requirements/' . $projects->id . '/' . $requirements->position . '/share') }}">

            <div class="form-group">
              <label for="emailInput">Enter Email Address</label>
              <div class="input-group">
                <input type="email" name="email" class="form-control" id="emailInput" placeholder="name@example.com" required>
                <button id="shareViaEmailBtn" class="btn btn-outline-primary">
                    <i class="far fa-envelope"></i> Share via Email
                </button>
              </div>
            </div>
        </form>
        <form action="{{url('contractuals/share/whatsapp')}}" id="form" method="post">
        @csrf
            <input type="hidden" name="url" value="{{ url('/contractuals/requirements/' . $projects->id . '/' . $requirements->position . '/share') }}">
            
            <div class="form-group">
              <label for="whatsappInput">Enter WhatsApp Number</label>
              <div class="input-group">
                <input type="tel" name="mobile" class="form-control" id="whatsappInput" placeholder="+1234567890" required>
                <button id="shareViaWhatsAppBtn" class="btn btn-outline-success">
                <i class="fab fa-whatsapp"></i> Share via WhatsApp
              </button>
              </div>
            </div>
        </form>
       
      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <div class="col-12 text-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const acceptTermsCheckbox = document.getElementById('acceptTerms');
    const saveChangesBtn = document.getElementById('saveChangesBtn');

    acceptTermsCheckbox.addEventListener('change', function () {
      if (acceptTermsCheckbox.checked) {
        saveChangesBtn.removeAttribute('disabled');
    } else {
        saveChangesBtn.setAttribute('disabled', 'true');
    }
});
      $('#applyButton').on('click', function() {
    const applicationMode = $('#applicationMode').val();
    const currentUrl = window.location.href;
    const requirementId = $(this).data('requirement-id');
    const position = $(this).data('position');

    if (applicationMode === 'Quick Mode') {
        const quickModeUrl = "{{ route('application_quick', ['requirement_id' => 'REQUIREMENT_ID', 'position' => 'POSITION']) }}"
            .replace('REQUIREMENT_ID', requirementId)
            .replace('POSITION', position);
        window.location.href = quickModeUrl;
    } else {
        const isAuthenticated = $('#isAuthenticated').val() === 'true';
        if (isAuthenticated) {
            $('#apply').modal('show');
        } else {
            sessionStorage.setItem('intendedUrl', currentUrl);
            window.location.href = "{{ route('login') }}";
        }
    }
});
});
</script>
</body>
</html>



