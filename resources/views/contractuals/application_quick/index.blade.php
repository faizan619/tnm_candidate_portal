@extends('layouts.app')

@section('content')
 <div class="container p-0">
    <div class="row justify-content-center">
        <div class="col-md-7">
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <div class="row">
                        <div class="col-8">{{ __('Application Form') }} (Quick Mode)</div>
                        <div class="col text-end">
                            <!-- <a href="{{ url()->previous() }}" class="btn btn-sm btn-light"><i class="fa-solid fa-arrow-left"></i> Back</a> -->
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('application_quick.store') }}" id="form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="requirement_id" value="{{$requirements->id}}">
                        <input type="hidden" name="job_type" value="contractual">
                         <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="position">Post Applied for <span class="text-danger">*</span></label>
                                <input type="text" name="position" class="form-control readonly" value="{{$position}}" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label for="location">Locations <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control readonly" value="{{$requirements->locations}}" readonly required>
                            </div>
                        </div> 


                        <div class="text-center text-muted mb-2">----: Personal Details :----</div>
                        

                        <div class="row mb-2">
                            <div class="col-md-6 mb-2">
                                <label for="name">Name (As Per Adhar Card) <span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}" required  maxlength="40">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-2">
                                <label>Date of Birth <span class="text-danger">*</span></label>
                                <input type="text" name="date_of_birth" class="form-control date" placeholder="Select date.." value="{{old('date_of_birth')}}" readonly required data-parsley-age>

                            </div>
                           
                            <div class="col-md-6 mb-2">
                                <label>Gender <span class="text-danger">*</span></label>
                               <div class="row">
                                @foreach($genders as $gender)
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_{{ $loop->index }}" required value="{{ $gender->value_description }}" {{ $gender->value_description == old('gender') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender_{{ $loop->index }}">
                                                {{ $gender->value_description }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>



                            </div>
                           
                            <div class="col-md-6 mb-2">
                                <label for="father_name" style="font-size: 14px;">Father's Name( as per AADHAR CARD ) <span class="text-danger">*</span></label>
                                <input type="text" name="father_name" class="form-control" value="{{old('father_name',isset($applicationPersonal) ? $applicationPersonal->father_name : '')}}" required maxlength="40">
                            </div>
                           
                            <div class="col-md-6 mb-2">
                                <label>Mobile Number <span class="text-danger">*</span></label>
                                <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{old('mobile')}}" required maxlength="10" data-parsley-pattern="^[789]\d{9}$">

                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Email Address </label>
                                <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" maxlength="30">

                            </div>


                        </div>

                        <div class="text-center text-muted mb-2">----: Education Details :----</div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>Qualification <span class="text-danger">*</span></label>
                                <select name="qualification" class="form-control" required id="qualification">
                                    <option disabled selected>Select Qualification</option>
                                    @foreach($qualifications as $qualification)
                                    <option>{{$qualification->level1}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="any_graduate">
                                <label>Stream <span class="text-danger">*</span></label>
                                <select name="qualification" class="form-control" required>
                                    <option disabled selected>Select Qualification</option>
                                    @foreach($streams as $stream)
                                    <option>{{$stream->value_description}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                                <div class="row ">
                                    <div class="col-md-6">
                                        <div>
                                        <label>Present Address <span class="text-danger">*</span></label>
                                        <textarea name="address" class="form-control" data-type="present" required maxlength="100">{{old('address')}}</textarea>
                                        </div>
                                    
                                    
                                        <div class="row" id="address">
                                            <div class="col-4 pe-0">
                                                <label>Pincode <span class="text-danger">*</span></label>
                                                <input type="text" name="present_pincode" class="form-control" autocomplete="off" value="{{old('present_pincode')}}" data-type="present" required>

                                                <span class="text-danger pinerror" data-type="present">@error('present_pincode') {{ $message }} @enderror</span>
                                            </div>
                                            <div class="col-4 ps-0 pe-0">
                                                <label>State</label>
                                                <input type="text" name="present_state" class="form-control readonly" readonly value="{{old('present_state')}}" data-type="present">
                                                <span class="text-danger" data-type="present">@error('present_state') {{ $message }} @enderror</span>
                                            </div>
                                            <div class="col-4 ps-0">
                                                <label>District</label>
                                                <input type="text" name="present_district" class="form-control readonly" readonly value="{{old('present_district')}}" data-type="present">
                                                <span class="text-danger" data-type="present">@error('present_district') {{ $message }} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>CV Upload <span class="text-danger">*</span></label>
                                        <input type="file" name="cv_file" class="form-control" required>
                                    </div>
                                </div>


                            

                        <div class="row mb-2 mt-4">
                           <div class="col-md-4 m-auto">
                            <label>Captcha <span class="text-danger">*</span></label>
                            <div class="captcha">
                                <span>{!! captcha_img() !!}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-refresh"><i class="fas fa-sync-alt"></i></button>
                            </div>
                            <input id="captcha" type="text" class="form-control @error('captcha') is-invalid @enderror" name="captcha" required>
                            @error('captcha')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                        <div class="row mb-0 mt-4">
                            <div class="col-md-3 m-auto text-center">
                                
                                <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#exampleModal">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <h3>Terms & Conditions</h3>
        <div class="responsive" 
            {!!$projects->terms_conditions!!}
        </div>

        <h3>General Instructions</h3>
        {!!$projects->general_instructions_candidate!!}

        <div class="row p-4">

            <div class="form-check text-danger" style="font-size:18px">
              <input type="checkbox" class="form-check-input" id="acceptTerms" style="border: solid 2px #494949 !important;">
              <label class="form-check-label" for="acceptTerms"><b>I accept the above terms and conditions and <a href="https://www.tnmhr.com/privacy-policy/" target="_blank">privacy policy</a></b></label>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Select the modal, checkbox, and the apply button
        var acceptCheckbox = document.getElementById('acceptTerms');
        var submitButton = document.querySelector('button[data-toggle="modal"]');
        var modal = document.getElementById('exampleModal');
        var modalCloseButton = modal.querySelector('.close');
        
        // Listen for checkbox change
        acceptCheckbox.addEventListener('change', function() {
            if (this.checked) {
                submitButton.type = 'submit';  // Change button type to submit when checked
                submitButton.removeAttribute('data-toggle');  // Remove data-toggle for modal
                submitButton.removeAttribute('data-target');  // Remove data-target for modal
            } else {
                submitButton.type = 'button';  // Reset the button type to button if unchecked
                submitButton.setAttribute('data-toggle', 'modal');  // Add back modal trigger
                submitButton.setAttribute('data-target', '#exampleModal');  // Add modal target back
            }
        });

        // Optional: If the user closes the modal without accepting, reset the button
        modalCloseButton.addEventListener('click', function() {
            submitButton.type = 'button';  // Keep the button type as 'button' if modal is closed
            submitButton.setAttribute('data-toggle', 'modal');  // Add modal trigger back
            submitButton.setAttribute('data-target', '#exampleModal');  // Add modal target back
            acceptCheckbox.checked = false;  // Uncheck the terms and conditions checkbox
        });
    });
</script>


@endsection

@section('script')
<script>
    var fetchStateDistrictUrl = "{{ route('fetch.state.district') }}";
</script>
<script src="{{asset('js/fetch_pincode.js')}}"></script>
<script src="{{asset('js/parsley.min.js')}}"></script>
<script>
    $('#form').parsley();
    
    window.Parsley.addValidator('age', {
                validateString: function(value) {
                    var dateOfBirth = new Date(value);
                    var today = new Date();
                    var age = today.getFullYear() - dateOfBirth.getFullYear();
                    var m = today.getMonth() - dateOfBirth.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < dateOfBirth.getDate())) {
                        age--;
                    }
                    return age >= 18;
                },
                messages: {
                    en: 'You must be at least 18 years old.'
                }
            });
    document.querySelector('.btn-refresh').addEventListener('click', function() {
        fetch("{{ route('refresh-captcha') }}")
            .then(response => response.json())
            .then(data => {
                document.querySelector('.captcha span').innerHTML = data.captcha;
            });
    });

    $(document).ready(function() {
     $('#any_graduate').hide();
    $('#qualification').on('change', function() {
        var selectedQualification = $(this).val();
        if (selectedQualification === 'Any Graduate') {
            $('#any_graduate').show();
        } else {
            $('#any_graduate').hide();
        }
    });


    $('input[name="present_pincode"], input[name="pincode"]').on('input', function() {
        var type = $(this).data('type');
        
        handlePincodeInput(type);
    });
});


    
</script>
@endsection
