@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-10">
            <h4>My Profile / Personal Details Edit</h4>
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <div class="row"> 
                        <div class="col-10">Edit</div>
                        <div class="col-2 text-end">
                            <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa-solid fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                   <form method="POST" action="{{ route('myprofile.personal_details.update') }}" id="form" enctype="multipart/form-data">
                    @csrf 
                     <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="name">Name (As Per Adhar Card) <span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name',$candidate->name) }}" required autocomplete="name" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label>Gender <span class="text-danger">*</span></label>
                                <div class="row">
                                    @foreach($genders as $gender)
                                    <div class="col">
                                        <div class="form-check">
                                          <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" required value="{{$gender->value_description}}" {{$gender->value_description==$candidate->gender ? 'checked':''}}>
                                          <label class="form-check-label" for="flexRadioDefault1">
                                            {{$gender->value_description}}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>

                    <div class="row mb-2">
                       <div class="col-md-6">
                        <label>Email Address <span class="text-danger">*</span></label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email',$candidate->email) }}" required autocomplete="email" data-parsley-remote="{{ route('validate.email') }}" data-parsley-remote-validator='custom' data-parsley-remote-message="Email already exists" disabled>

                    </div>

                    <div class="col-md-6">
                        <label>Mobile Number <span class="text-danger">*</span></label>
                        <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile',$candidate->mobile) }}" required autocomplete="mobile" data-parsley-pattern="^[789]\d{9}$" data-parsley-remote="{{ route('validate.mobile') }}" data-parsley-remote-validator='custom' data-parsley-remote-message="Mobile number already exists" disabled>

                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Present Address</label>
                                        <textarea name="present_address" class="form-control" data-type="present">{{old('present_address',$candidate->present_address)}}</textarea>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="row" id="present_address">
                                            <div class="col pe-0">
                                                <label>Pincode</label>
                                                <input type="text" name="present_pincode" class="form-control" autocomplete="off" value="{{ old('present_pincode',$candidate->present_pincode)}}" data-type="present">

                                                <span class="text-danger pinerror" data-type="present">@error('present_pincode') {{ $message }} @enderror</span>
                                            </div>
                                            <div class="col-5 ps-0 pe-0">
                                                <label>State</label>
                                                <input type="text" name="present_state" class="form-control readonly" readonly value="{{ old('present_state',$candidate->present_state)}}" data-type="present">
                                                <span class="text-danger" data-type="present">@error('present_state') {{ $message }} @enderror</span>
                                            </div>
                                            <div class="col ps-0">
                                                <label>District</label>
                                                <input type="text" name="present_district" class="form-control readonly" readonly value="{{old('present_district',$candidate->present_district)}}" data-type="present">
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
                                        <textarea class="form-control" name="permanent_address" data-type="permanent">{{old('permanent_address',$candidate->permanent_address)}}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col pe-0">
                                                <label>Pincode</label>
                                                <input type="text" name="permanent_pincode" class="form-control" autocomplete="off" value="{{old('permanent_pincode',$candidate->present_pincode)}}" data-type="permanent">
                                                <span class="text-danger pinerror" data-type="permanent">@error('permanent_pincode') {{ $message }} @enderror</span>
                                            </div>
                                            <div class="col-5 ps-0 pe-0">
                                                <label>State</label>
                                                <input type="text" name="permanent_state" class="form-control readonly" readonly value="{{old('', $candidate->permanent_state)}}" data-type="permanent">
                                                <span class="text-danger" data-type="permanent">@error('permanent_state') {{ $message }} @enderror</span>
                                            </div>
                                            <div class="col ps-0">
                                                <label>District</label>
                                                <input type="text" name="permanent_district" class="form-control readonly" readonly value="{{old('', $candidate->permanent_district) }}" data-type="permanent">
                                                <span class="text-danger" data-type="permanent">@error('permanent_district') {{ $message }} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>


                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>Date of Birth <span class="text-danger">*</span></label>
                       <input type="text" name="date_of_birth" class="form-control date" placeholder="Select date.." value="{{ \Carbon\Carbon::parse($candidate->date_of_birth)->format('d/m/Y') }}" readonly required>
                    </div>
                    <div class="col-md-6">
                        <label>Industry</label>
                        <select name="industry" class="form-control">
                            <option value="null">Select Industry</option>
                            @foreach($industries as $industry)
                            <option value="{{ $industry->value_description }}" {{ old('industry',$candidate->industry) == $industry->value_description ? 'selected' : '' }}>{{ $industry->value_description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


      <table class="table table-sm table-bordered">
    <thead class="table-dark">
        <tr>
            <th class="text-white">Language</th>
            <th class="text-white">Skills</th>
            <th class="text-end">
                <button type="button" class="btn btn-sm btn-danger" id="add-language-btn">
                    <i class="fas fa-plus"></i>
                </button>
            </th>
        </tr>
    </thead>
    <tbody id="languages-container">
        @foreach($candidateLanguages as $index => $language)
            <tr class="language-row">
                <td>
                    <select name="languages[{{ $index }}][name]" class="form-select" required>
                        <option value="">Select Language</option>
                        @foreach($languages as $lang)
                            <option value="{{ $lang->value_description }}" {{ $language->language == $lang->value_description ? 'selected' : '' }}>
                                {{ $lang->value_description }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <div class="row text-center pt-2">
                        <div class="col">
                            <label><input type="checkbox" name="languages[{{ $index }}][skills][]" value="Read" 
                                {{ $language->read ? 'checked' : '' }}>
                                Read
                            </label>
                        </div>
                        <div class="col border-start">
                            <label><input type="checkbox" name="languages[{{ $index }}][skills][]" value="Write" 
                                {{ $language->write ? 'checked' : '' }}>
                                Write
                            </label>
                        </div>
                        <div class="col border-start">
                            <label><input type="checkbox" name="languages[{{ $index }}][skills][]" value="Speak" 
                                {{ $language->speak ? 'checked' : '' }}>
                                Speak
                            </label>
                        </div>
                    </div>
                </td>
                <td>
                    @if($index > 0)
                        <button type="button" class="btn btn-sm btn-danger remove-language-btn">
                            <i class="fas fa-minus"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        <!-- Placeholder row if less than 3 rows exist -->
       
    </tbody>
</table>


               <!--  <div class="row mb-2">
                    <div class="col-md-6">
                        <label>Photo Upload</label>
                        <div class="photo_preview">

                           @php
                                $photo = $candidateDocuments->where('document_type', 'photo')->first();
                            @endphp
                            @if($photo)
                                <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Photo Preview" id="photoPreview" style="max-width: 100%;display: block;">
                            @else
                                <h3>No Photo Uploaded</h3>
                            @endif
                        </div>
                        <input type="file" name="photo" id="photo" class="form-control" data-parsley-filetype="jpeg, jpg, png"
                        data-parsley-filetype-message="Only JPEG, JPG, and PNG files are allowed.">
                    </div>
                    <div class="col-md-6">
                        <label>Signature Upload</label>
                        <div class="photo_preview">
                            @php
                                $signature = $candidateDocuments->where('document_type', 'signature')->first();
                            @endphp
                            @if($signature)
                                <img src="{{ asset('storage/' . $signature->file_path) }}" alt="Signature Preview" id="signaturePreview" style="max-width: 100%;display: block;">
                            @else
                                <h3>No Signature Uploaded</h3>
                            @endif
                                             
                        </div>
                        <input type="file" name="signature" class="form-control" id="signature" data-parsley-filetype="jpeg, jpg, png"
                        data-parsley-filetype-message="Only JPEG, JPG, and PNG files are allowed.">
                    </div>
                </div> -->

                     <div class="row mb-2">
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

                        <div class="row mb-0 mt-4">
                            <div class="col-md-3 m-auto text-center">
                                
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                   
                </div>
                <div class="card-footer">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('js/fetch_pincode.js')}}"></script>
<script src="{{asset('js/parsley.min.js')}}"></script>
<script>
     $('#form').parsley();

     document.querySelector('.btn-refresh').addEventListener('click', function() {
        fetch("{{ route('refresh-captcha') }}")
            .then(response => response.json())
            .then(data => {
                document.querySelector('.captcha span').innerHTML = data.captcha;
            });
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

document.addEventListener('DOMContentLoaded', function () {
    const addLanguageBtn = document.getElementById('add-language-btn');
    const languagesContainer = document.getElementById('languages-container');

    // Function to remove a row
    function removeRow(event) {
        event.target.closest('.language-row').remove();
        updateDropdownOptions(); // Update options after a row is removed
    }

    // Function to update dropdown options
    function updateDropdownOptions() {
        const rows = languagesContainer.querySelectorAll('.language-row');
        const selectedLanguages = Array.from(rows).map(row => row.querySelector('select').value);

        rows.forEach((row, index) => {
            const select = row.querySelector('select');
            const options = Array.from(select.options);

            // Remove options that are selected in other rows
            options.forEach(option => {
                if (selectedLanguages.includes(option.value) && option.value !== select.value) {
                    option.remove();
                }
            });

            // Add back options that are not selected in any row
            let availableLanguages = @json($languages->pluck('value_description'));
            availableLanguages.forEach(language => {
                if (!Array.from(select.options).some(option => option.value === language)) {
                    if (!selectedLanguages.includes(language)) {
                        const newOption = document.createElement('option');
                        newOption.value = language;
                        newOption.textContent = language;
                        select.appendChild(newOption);
                    }
                }
            });
        });
    }

    // Add event listener for adding a new language row
    addLanguageBtn.addEventListener('click', function () {
        const rowCount = languagesContainer.querySelectorAll('.language-row').length;

        // Check if the number of rows is less than 3
        if (rowCount >= 3) {
            alert('You can only add up to 3 languages.');
            return;
        }

        // Create a new row
        const newRow = document.createElement('tr');
        newRow.className = 'language-row';

        // Create language options for new row
        let languageOptions = '<option>Select Language</option>';
        @foreach($languages as $language)
            languageOptions += `<option value="{{ $language->value_description }}">{{ $language->value_description }}</option>`;
        @endforeach

        newRow.innerHTML = `
            <td>
                <select name="languages[${rowCount}][name]" class="form-select">
                    ${languageOptions}
                </select>
            </td>
            <td>
                <div class="row text-center pt-2">
                    <div class="col">
                        <label><input type="checkbox" name="languages[${rowCount}][skills][]" value="Read"> Read</label>
                    </div>
                    <div class="col border-start">
                        <label><input type="checkbox" name="languages[${rowCount}][skills][]" value="Write"> Write</label>
                    </div>
                    <div class="col border-start">
                        <label><input type="checkbox" name="languages[${rowCount}][skills][]" value="Speak"> Speak</label>
                    </div>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-language-btn">
                    <i class="fas fa-minus"></i>
                </button>
            </td>
        `;

        // Append the new row to the table
        languagesContainer.appendChild(newRow);

        // Attach event listener to the new remove button
        newRow.querySelector('.remove-language-btn').addEventListener('click', removeRow);

        // Update dropdown options
        updateDropdownOptions();
    });

    // Attach remove button event listener to existing rows
    languagesContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-language-btn')) {
            removeRow(event);
        }
    });

    // Initial call to set options on page load
    updateDropdownOptions();
});



    document.addEventListener("DOMContentLoaded", function() {
    // Function to show the image preview
        function showPreview(input, previewId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewElement = document.getElementById(previewId);
                    const spanElement = previewElement.previousElementSibling;
                    if (previewElement) {
                        previewElement.src = e.target.result;
                        previewElement.style.display = 'block';
                    }
                    if (spanElement) {
                        spanElement.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        }

    // Attach event listeners to file input elements
        const photoInput = document.getElementById('photo');
        const signatureInput = document.getElementById('signature');

        if (photoInput) {
            photoInput.addEventListener('change', function() {
                showPreview(this, 'photoPreview');
            });
        }

        if (signatureInput) {
            signatureInput.addEventListener('change', function() {
                showPreview(this, 'signaturePreview');
            });
        }
    });
</script>
@endsection