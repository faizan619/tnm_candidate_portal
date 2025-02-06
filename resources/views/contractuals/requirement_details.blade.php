@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 m-auto">
         @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show">{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show">{!! session('error') !!} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        @endif

        <div class="card">
            <div class="card-header bg-dark">
                <div class="row text-white">
                        Requirement Details
                    
                </div>
            </div>
            <div class="card-body bg-pink">
                <p class="text-center"><strong></strong></p>
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-sm table-bordered table-striped">
                            <tr>
                               
                                <td>Position</td><td>{{$requirements->position}}</td>
                                
                            </tr><tr>
                                <td>Start Date Time</td><td>{{ \Carbon\Carbon::parse($requirements->start_date_time)->format('d/m/Y H:i:s') }}</td>
                            </tr><tr>
                                <td>Expiry Date Time</td><td>{{ \Carbon\Carbon::parse($requirements->expiry_date_time)->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                
                                     @if($requirements->extended_date_time)
                                     <td>Extended Date Time</td><td>
                                        {{ \Carbon\Carbon::parse($requirements->extended_date_time)->format('d/m/Y H:i:s') }}
                                    </td>
                                    @endif
                                
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
                                @php
    // Assuming $requirements->locations is a JSON string
    // Convert the JSON string into an array
    $locationsArray = json_decode($requirements->locations, true);

    // Clean the array by removing any invalid entries (like null)
    $cleanedLocationsArray = array_filter($locationsArray, function($location) {
        return !empty(trim($location)) && strtolower(trim($location)) !== 'null';
    });

    // Join the cleaned locations array back into a string separated by new lines
    $locationsFormatted = implode('<br>', $cleanedLocationsArray);
@endphp

<td>Locations</td>
<td>{!! $locationsFormatted !!}</td>

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
                            @if ($requirements->reserved_for_women != 0)
                            <tr>
                                <td>No of Positions Reserved for Women</td>
                                <td>
                                     
                                        {{ $requirements->reserved_for_women }}
                                </td>
                            </tr>
                            @endif
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
                            @if(!$age_limits->isEmpty())
                             <tr>
                                <td>Reservation Details</td>
                                <td>
                                    @php
                                        // Check if there is at least one non-empty 'caste' in the age_limits
                                        $showCasteColumn = $age_limits->contains(function ($age_limit) {
                                            return !empty($age_limit->caste);
                                        });
                                    @endphp

                                    @if($requirements->age_display == "No")

                                    @else
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            @if($showCasteColumn)
                                                <th>Caste</th>
                                            @endif
                                            <th>Age Limit</th>
                                            <th>Relaxation Limit</th>
                                            <th>Age as on</th>
                                        </tr>
                                        @foreach($age_limits as $age_limit)
                                        <tr>
                                            @if($showCasteColumn)
                                                <td>{{ $age_limit->caste }}</td>
                                            @endif
                                            <td>{{ $age_limit->age_limit }}</td>
                                            <td>{{ $age_limit->relaxation_limit }}</td>
                                            <td>{{ $age_limit->age_as_on }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </table>

                                    
                                        
                                </td>
                            </tr>
                            @endif
                            
                        </table>

                    </div>
                    
                </div>
                <input type="hidden" id="isAuthenticated" value="{{ Auth::check() ? 'true' : 'false' }}">
                <input type="hidden" id="applicationMode" value="{{ $projects->application_mode }}">
                @if($requirements->status==1)
                <div class="text-center">
                    
                    <button class="btn btn-danger m-4" data-bs-toggle="modal" data-bs-target="#share">Share <i class="fa-solid fa-share-from-square"></i></button>
                    <button id="applyButton" class="btn btn-danger" data-application-mode="{{ $projects->application_mode }}"    data-requirement-id="{{ $requirements->id }}" data-position="{{ $requirements->position }}">Apply <i class="fa-solid fa-hand-point-up"></i> </button>

                    
                    
                </div>
                @endif
            </div>

            <div class="card-footer">
                   <a href="{{url()->previous()}}" class="btn btn-secondary"><i class="fa-solid fa-angles-left"></i> Back</a>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="apply" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable modal-dialog-centered" role="document">
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

        <div class="row p-4">

            <div class="form-check text-danger" style="font-size:18px;">
              <input type="checkbox" class="form-check-input" id="acceptTerms" style="border: solid 2px #494949 !important;">
              <label class="form-check-label" for="acceptTerms"><b>I accept the above terms and conditions and <a href="https://www.tnmhr.com/privacy-policy/" target="_blank">privacy policy</a></b></label>
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <div class="col-8 text-left">
            
          </div>
          <div class="col-4">

           <button class="btn btn-danger float-end" id="saveChangesBtn" data-bs-target="#document_upload" data-bs-toggle="modal" data-bs-dismiss="modal" disabled>Proceed</button>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- modal document upload -->
<div class="modal fade" id="document_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Document To Be Uploaded</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
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
            
          </div>
          <div class="col-4">

           <button onclick="window.location='{{ route('application.personal_details', ['requirement_id' => $requirements->id, 'position' => $requirements->position]) }}'" class="btn btn-danger float-end" id="saveChangesBtn">Proceed</button>


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
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Share options -->
        <form action="{{url('contractuals/share/email') }}" id="form" method="post">
            @csrf
            <input type="hidden" name="url" value="{{ url('/contractuals/requirements/' . $projects->id . '/' . $requirements->position . '/share') }}">

            <div class="mb-2">
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
           <!--  <input type="hidden" name="url" value="{{ url('/contractuals/requirements/' . $projects->id . '/' . $requirements->position . '/share') }}"> -->
            <input type="hidden" name="url" value="{{URL::current()}}">
            
            <div class="mb-2">
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
            
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
<script>
 $(document).ready(function() {
    const $acceptTermsCheckbox = $('#acceptTerms');
    const $saveChangesBtn = $('#saveChangesBtn');
    
    $acceptTermsCheckbox.on('change', function() {
      if ($acceptTermsCheckbox.is(':checked')) {
        $saveChangesBtn.removeAttr('disabled');
      } else {
        $saveChangesBtn.attr('disabled', 'true');
      }
    });

    // Add the click event listener for the Apply button
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

@endsection