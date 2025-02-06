@extends('layouts.app')

@section('content')
 <style>
        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 10px;
        }
        .tag {
            display: flex;
            align-items: center;
            padding: 2px 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background-color: #f1f1f1;
            font-size: 10px;
        }
        .tag .remove {
            margin-left: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .dropdown {
            margin-bottom: 10px;
        }
        #parsley-id-9{
            margin-top:-35px;
        }
    </style>
 <div class="container p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">
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
                        <div class="col-8">{{ __('Application Form') }} (Personal Details)</div>
                        <div class="col text-end">
                            <!-- <a href="{{ url()->previous() }}" class="btn btn-sm btn-light"><i class="fa-solid fa-arrow-left"></i> Back</a> -->
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('application.personal_details.store') }}" id="form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="requirement_id" value="{{$requirements->id}}">
                        <input type="hidden" name="job_type" value="contractual">
                         <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="position">Post Applied for <span class="text-danger">*</span></label>
                                <input type="text" name="position" class="form-control readonly" value="{{$position}}" readonly required>
                            </div>
                            <div class="col-md-8">
                                <!--  @php
                                    $locationsArray = explode(',', $requirements->locations);
                                    $cleanedLocationsArray = array_filter($locationsArray, function($location) {
                                        return !empty(trim($location)) && strtolower(trim($location)) !== 'null';
                                    });
                                    $locations = implode(', ', $cleanedLocationsArray);
                                @endphp
                                <label for="location">Locations <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control readonly" value="{{$locations}}" readonly required> -->
                                 @php
									
                                    // Assuming $requirements->locations is a comma-separated string
                                    $locationsArray = json_decode($requirements->locations, true);

                                    // Clean up the array: trim spaces and filter out empty or invalid entries
                                    $cleanedLocationsArray = array_filter($locationsArray, function($location) {
                                        $trimmed = trim($location);
                                        return !empty($trimmed) && strtolower($trimmed) !== 'null';
                                    });
                                @endphp
                                 <label>Location Preference<span>*</span></label>
                                    <div class="tags-container" id="tags-container">
                                         @if (isset($selectedLocations) && !empty($selectedLocations))
                                            @foreach ($selectedLocations as $location)
                                                <span class="tag">{{ $location }}<span class="remove-tag" onclick="removeTag(this)">&times;</span></span>
                                            @endforeach
                                        @endif
                                    </div>

                                    <select id="tag-select" class="dropdown form-select">
                                        <option value="">Select Locations</option>
                                        @foreach ($cleanedLocationsArray as $location)
                                            <option value="{{ trim($location) }}">{{ trim($location) }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Hidden input field to store selected tags -->
                                    <input type="text" name="location" id="locations-input" value="{{ $applicationPersonal ? json_encode($selectedLocations) : '' }}" data-parsley-required="true" data-parsley-required-message="Please select at least one location." style="opacity: 0; height: 0; margin-top:-20px">
                            </div>
                        </div> 


                        <div class="text-center text-muted mb-2">----: Personal Details :----</div>
                        

                        <div class="row mb-2">
                            <div class="col-md-6 mb-2">
                                <label for="name">Name (As Per Aadhar Card) <span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control readonly @error('name') is-invalid @enderror" name="name" value="{{ $candidate_user->name}}" required readonly>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="id_proof_no" style="font-size: 14px;">ID Proof</label>
                                <div class="row">
                                    <div class="col-4 pe-0">
                                         <select class="form-select" name="id_proof_type" id="id_proof_type"> <!-- data-parsley-required-message="Please select an ID proof type" -->
                                            <option value="">Select ID Proof Type</option>
                                            <option value="Driving License" {{ old('id_proof_type', $applicationPersonal->id_proof_type ?? '') == 'Driving License' ? 'selected' : '' }}>Driving License</option>
                                            <option value="Voter ID" {{ old('id_proof_type', $applicationPersonal->id_proof_type ?? '') == 'Voter ID' ? 'selected' : '' }}>Voter ID</option>
                                            <option value="PAN Card" {{ old('id_proof_type', $applicationPersonal->id_proof_type ?? '') == 'PAN Card' ? 'selected' : '' }}>PAN Card</option>
                                            <option value="Passport" {{ old('id_proof_type', $applicationPersonal->id_proof_type ?? '') == 'Passport' ? 'selected' : '' }}>Passport</option>
                                            <option value="Aadhar Card" {{ old('id_proof_type', $applicationPersonal->id_proof_type ?? '') == 'Aadhar Card' ? 'selected' : '' }}>Aadhar Card</option>
                                          </select>
                                    </div>
                                    <div class="col-8 ps-0" id="IdProof_no" style="display:none">
                                        <input id="id_proof_no" type="text" class="form-control @error('id_proof_no') is-invalid @enderror" name="id_proof_no" value="{{ old('id_proof_no', isset($applicationPersonal) ? $applicationPersonal->id_proof_no : '') }}" placeholder="ID Proof No" data-parsley-required="false" maxlength="20">
                                        <div id="id_proof_no_error" class="text-danger mt-1"></div> <!-- Error message container -->
                                    </div>
                                </div>
    
                                @error('id_proof_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="caste" style="font-size: 14px;">Caste Category</label>
                                <select name="caste" class="form-select">
                                    <option value="">Select Your Caste</option>
                                    @foreach($project_caste as $caste)
                                        <option value="{{ $caste->caste }}" {{ $caste->caste == old('caste', $applicationPersonal->caste ?? '') ? 'selected' : '' }}>
                                            {{ $caste->caste }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Date of Birth <span class="text-danger">*</span></label>
                                <input type="text" name="date_of_birth" class="form-control date" placeholder="Select date.." value="{{ isset($applicationPersonal->date_of_birth) ? \Carbon\Carbon::parse($applicationPersonal->date_of_birth)->format('d/m/Y') : \Carbon\Carbon::parse($candidate_user->date_of_birth)->format('d/m/Y') }}" readonly required data-parsley-age>


                            </div>
                           
                            <div class="col-md-6 mb-2">
                                <label for="birth_place" style="font-size: 14px;">Place of Birth <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="birth_place" value="{{old('birth_place',isset($applicationPersonal) ? $applicationPersonal->place_of_birth:'')}}" placeholder="" required maxlength="15">

                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Gender <span class="text-danger">*</span></label>
                               <div class="row">
                                @foreach($genders as $gender)
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_{{ $loop->index }}" required value="{{ $gender->value_description }}" {{ $gender->value_description == old('gender', $applicationPersonal->gender ?? $candidate_user->gender) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender_{{ $loop->index }}">
                                                {{ $gender->value_description }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>



                            </div>
                            <div class="col-md-6 mb-2">
                                
                               <div class="row">
                                    <div class="col-md-6">
                                        <label>Physically Challenged <span class="text-danger">*</span></label>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="physically_challenged" id="physically_challenged_yes" required value="Yes" {{ old('physically_challenged', $applicationPersonal->physically_challenged ?? '') == 'Yes' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="physically_challenged_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="physically_challenged" id="physically_challenged_no" required value="No" {{ old('physically_challenged', $applicationPersonal->physically_challenged ?? '') == 'No' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="physically_challenged_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="physically_challenged_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="percentage_of_disability">
                                        <label>% of Disability</label>
                                        <input type="number" name="percentage_of_disability" min="1" max="100" value="{{ old('percentage_of_disability', isset($applicationPersonal) ? $applicationPersonal->percentage_of_disability : '') }}" class="form-control">

                                    </div>
                                    
                                </div>
                            </div>


                            <div class="col-md-6 mb-2">
                                <label for="religion" style="font-size: 14px;">Religion</label>
                                <select name="religion" class="form-control">
                                    <option value="">Select Your Religion</option>
                                    @foreach($religions as $religion)
                                        <option value="{{ $religion->value_description }}" {{ old('religion', $applicationPersonal->religion ?? '') == $religion->value_description ? 'selected' : '' }}>
                                            {{ $religion->value_description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Present Address</label>
                                        <textarea name="present_address" class="form-control" data-type="present" maxlength="100">@if($applicationPersonal){{$applicationPersonal->present_address}}@else{{$candidate_user->present_address}}@endif</textarea>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="row" id="present_address">
                                            <div class="col pe-0">
                                                <label>Pincode</label>
                                                <input type="text" name="present_pincode" class="form-control" autocomplete="off" value="{{ $applicationPersonal ? $applicationPersonal->present_pincode : $candidate_user->present_pincode }}" data-type="present">

                                                <span class="text-danger pinerror" data-type="present">@error('present_pincode') {{ $message }} @enderror</span>
                                            </div>
                                            <div class="col-5 ps-0 pe-0">
                                                <label>State</label>
                                                <input type="text" name="present_state" class="form-control readonly" readonly value="{{ $candidate_user->present_state}}" data-type="present">
                                                <span class="text-danger" data-type="present">@error('present_state') {{ $message }} @enderror</span>
                                            </div>
                                            <div class="col ps-0">
                                                <label>District</label>
                                                <input type="text" name="present_district" class="form-control readonly" readonly value="{{$candidate_user->present_district}}" data-type="present">
                                                <span class="text-danger" data-type="present">@error('present_district') {{ $message }} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="mb-0">Permanent Address</label>
                                            <div class="form-check m-0">
                                                <input class="form-check-input" name="sameas" type="checkbox" id="sameAsPresentAddress" {{ old('sameas') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sameAsPresentAddress">
                                                    Same as Present Address
                                                </label>
                                            </div>
                                        </div>
                                        <textarea class="form-control" name="permanent_address" data-type="permanent" maxlength="100">@if($applicationPersonal){{$applicationPersonal->permanent_address}}@else{{$candidate_user->permanent_address}}@endif</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col pe-0">
                                                <label>Pincode</label>
                                                <input type="text" name="permanent_pincode" class="form-control" autocomplete="off" value="{{ $applicationPersonal ? $applicationPersonal->present_pincode : $candidate_user->present_pincode }}" data-type="permanent">
                                                <span class="text-danger pinerror" data-type="permanent">@error('permanent_pincode') {{ $message }} @enderror</span>
                                            </div>
                                            <div class="col-5 ps-0 pe-0">
                                                <label>State</label>
                                                <input type="text" name="permanent_state" class="form-control readonly" readonly value="{{ $candidate_user->permanent_state}}" data-type="permanent">
                                                <span class="text-danger" data-type="permanent">@error('permanent_state') {{ $message }} @enderror</span>
                                            </div>
                                            <div class="col ps-0">
                                                <label>District</label>
                                                <input type="text" name="permanent_district" class="form-control readonly" readonly value="{{ $candidate_user->permanent_district }}" data-type="permanent">
                                                <span class="text-danger" data-type="permanent">@error('permanent_district') {{ $message }} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="caste" style="font-size: 14px;">Marital Status <span class="text-danger">*</span></label>
                                <select name="marital_status" class="form-select" required>
                                    <option selected disabled>Select Your Marital Status</option>
                                    @foreach($marital_status as $status)
                                        <option value="{{ $status->value_description }}" {{ $status->value_description == old('marital_status', $applicationPersonal->marital_status ?? '') ? 'selected' : '' }}>
                                            {{ $status->value_description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2" id="spouse">
                                <label for="caste" style="font-size: 14px;">Spouse Name as per (AADHAR CARD)</label>
                                <input type="text" name="spouse" class="form-control" maxlength="40">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="father_name" style="font-size: 14px;">Father's Name( as per AADHAR CARD ) <span class="text-danger">*</span></label>
                                <input type="text" name="father_name" class="form-control" value="{{old('father_name',isset($applicationPersonal) ? $applicationPersonal->father_name : '')}}" required maxlength="40">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="mother_name" style="font-size: 14px;">Mother Name( as per AADHAR CARD )  <span class="text-danger">*</span></label>
                                <input type="text" name="mother_name" class="form-control" value="{{old('mother_name',isset($applicationPersonal) ? $applicationPersonal->mother_name:'')}}" required maxlength="40">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Mobile Number <span class="text-danger">*</span></label>
                                <input id="mobile" type="text" class="form-control readonly @error('mobile') is-invalid @enderror" name="mobile" value="{{ $candidate_user->mobile}}" required readonly maxlength="10" data-parsley-pattern="^[789]\d{9}$">

                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Email Address <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control readonly @error('email') is-invalid @enderror" name="email" value="{{$candidate_user->email }}" required readonly maxlength="30">

                            </div>


                        </div>


                        <!-- <div class="row mb-2">
                           <div class="col-md-3 m-auto">
                            <label>Captcha</label>
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
 -->
                        <div class="row mb-0 mt-4">
                            <div class="col-md-8 m-auto text-center">
                                
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Save & Next') }}
                                </button>
                            </div>
                            <div class="col-md-8 mx-auto text-center mt-2">
                                <p>For any queries, please contact at <a href="mailto:{{$mailid}}">{{$mailid}}</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var fetchStateDistrictUrl = "{{ route('fetch.state.district') }}";
</script>
<script src="{{asset('js/fetch_pincode.js')}}"></script>
<script src="{{asset('js/parsley.min.js')}}"></script>
<script>

    
   $('#form').parsley({
            errorsContainer: function(parsleyField) {
                if (parsleyField.$element.attr('id') === 'id_proof_no') {
                    return $('#id_proof_no_error');
                }
                return parsleyField;
            }
        });

        window.Parsley.addValidator('age', {
    validateString: function(value) {
        // Log the input value for debugging
        console.log("Input value: ", value);

        // Split the input value by '/' and construct a valid date string
        var parts = value.split('/');
        if (parts.length === 3) {
            // Create a date in YYYY-MM-DD format
            var dateOfBirth = new Date(parts[2], parts[1] - 1, parts[0]); // Month is 0-indexed

            // Check if the date is valid
            if (isNaN(dateOfBirth.getTime())) {
                console.error("Invalid date of birth: ", value);
                return false; // Invalid date
            }

            var today = new Date();

            // Calculate the difference in years
            var age = today.getFullYear() - dateOfBirth.getFullYear();
            console.log("Calculated age: ", age); // Log the calculated age

            // Get the month and day of the birth date
            var birthMonth = dateOfBirth.getMonth();
            var birthDay = dateOfBirth.getDate();

            // Get the current month and day
            var currentMonth = today.getMonth();
            var currentDay = today.getDate();

            // Adjust age if the birthday has not occurred yet this year
            if (currentMonth < birthMonth || (currentMonth === birthMonth && currentDay < birthDay)) {
                age--;
            }

            // Return true if age is 18 or greater
            return age >= 18;
        } else {
            console.error("Invalid date format: ", value);
            return false; // Invalid format
        }
    },
    messages: {
        en: 'You must be at least 18 years old.'
    }
});



        window.Parsley.addValidator('requiredIfSelected', {
            requirementType: 'string',
            validateString: function(value, requirement) {
                var targetElement = $(requirement);
                return targetElement.val() ? !!value : true;
            },
            messages: {
                en: 'ID Proof number is required when an ID proof type is selected.'
            }
        });
		
		function toggleIdProofNoField() {
			if ($('#id_proof_type').val() !== "") {
				$('#IdProof_no').show();
				$('#id_proof_no').attr('data-parsley-required', 'true');
				// Set the correct maxlength and validation based on selected type
				setIdProofValidation();
			} else {
				$('#id_proof_no').val("");  
				$('#IdProof_no').hide();
				$('#id_proof_no').removeAttr('data-parsley-required'); 
				$('#id_proof_no').removeAttr('data-parsley-length');
			}
		}

     // Function to set specific validation rules based on ID proof type
function setIdProofValidation() {
    var proofType = $('#id_proof_type').val();
    $('#id_proof_no').removeAttr('data-parsley-length');

    if (proofType === "Aadhar Card") {
        $('#id_proof_no').attr('data-parsley-length', '[12,12]');
        $('#id_proof_no').attr('data-parsley-length-message', 'Aadhar number must be 12 digits.');
    } else if (proofType === "PAN Card") {
        $('#id_proof_no').attr('data-parsley-length', '[10,10]');
        $('#id_proof_no').attr('data-parsley-length-message', 'PAN must be 10 digits.');
    } else {
        // Remove any existing custom messages if not Aadhar or PAN
        $('#id_proof_no').removeAttr('data-parsley-length-message');
    }
}

// Add custom Parsley validator for length message
window.Parsley.addMessages({
    en: {
        length: 'Length error'
    }
});

// Change event handler for the ID proof type dropdown
$('#id_proof_type').on('change', function() {
    toggleIdProofNoField();
});

// Initial check on page load
    toggleIdProofNoField();




      if ($('input[name="physically_challenged"]:checked').val() === 'Yes') {
        $('#percentage_of_disability').show();
      } else {
        $('#percentage_of_disability').hide();
      }

      // Change event handler for the radio buttons
      $('input[name="physically_challenged"]').on('change', function() {
        if ($(this).val() === 'Yes') {
          $('#percentage_of_disability input').val(1);
          $('#percentage_of_disability').show();
        } else {
          $('#percentage_of_disability input').val('');
          $('#percentage_of_disability').hide();
        }
      });

      $('#spouse').hide();
      $('select[name="marital_status"]').on('change', function() {
            var selectedStatus = $(this).val();
            if (selectedStatus === 'Married') {
                $('#spouse').show();
            } else {
                $('#spouse').hide();
            }
        });

    $('input[name="present_pincode"], input[name="permanent_pincode"]').on('input', function() {
        var type = $(this).data('type');
        handlePincodeInput(type);
    });

    $('#sameAsPresentAddress').on('change', function() {
        if (this.checked) {
            $('textarea[name="permanent_address"]').val($('textarea[name="present_address"]').val());
            $('input[name="permanent_pincode"]').val($('input[name="present_pincode"]').val()).trigger('input');
        } else {
            $('textarea[name="permanent_address"]').val('');
            $('input[name="permanent_pincode"]').val('').trigger('input');
            $('input[name="permanent_state"]').val('');
            $('input[name="permanent_district"]').val('');
        }
    });


    // mutliple prefered location
     document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('tag-select');
    const tagsContainer = document.getElementById('tags-container');
    const locationsInput = document.getElementById('locations-input');

    // Function to add a tag
    function addTag(value) {
        if (![...tagsContainer.children].some(tag => tag.dataset.value === value)) {
            const tag = document.createElement('div');
            tag.className = 'tag';
            tag.dataset.value = value;
            tag.innerHTML = `
                ${value}
                <span class="remove">&times;</span>
            `;
            tagsContainer.appendChild(tag);

            tag.querySelector('.remove').addEventListener('click', () => {
                tag.remove();
                updateLocationsInput();
                updateDropdownOptions();
            });

            updateLocationsInput();
        }
    }

    // Function to update hidden input with tag values
    function updateLocationsInput() {
        const tags = [...tagsContainer.children].map(tag => tag.dataset.value);
        locationsInput.value = JSON.stringify(tags); 
    }

    // Function to update dropdown options based on selected tags
    function updateDropdownOptions() {
        const selectedValues = [...tagsContainer.children].map(tag => tag.dataset.value);
        [...select.options].forEach(option => {
            if (selectedValues.includes(option.value)) {
                option.style.display = 'none';
            } else {
                option.style.display = 'block';
            }
        });
    }

    select.addEventListener('change', (event) => {
        const value = event.target.value;
        if (value) {
            addTag(value);
            select.value = ''; // Reset the select value
            updateDropdownOptions();
            
        }
    });

    

    // Initial call to ensure dropdown options are correctly displayed
    updateDropdownOptions();
});


    

</script>
@endsection
