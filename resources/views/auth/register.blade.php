@extends('layouts.app')

@section('content')
<div class="container">
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

                    <!-- Displaying success messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
            <div class="card">
                <div class="card-header bg-danger text-white">{{ __('Register') }}</div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('register') }}" id="form" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center text-muted mb-2">----: Personal Details :----</div>
                        

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="name">Name (As Per Aadhar Card) <span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
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
                                          <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" required value="{{$gender->value_description}}" {{$gender->value_description=='Male' ? 'checked':''}}>
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
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" data-parsley-remote="{{ route('validate.email') }}" data-parsley-remote-validator='custom' data-parsley-remote-message="Email already exists">

                    </div>

                    <div class="col-md-6">
                        <label>Mobile Number <span class="text-danger">*</span></label>
                        <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile" data-parsley-pattern="^[789]\d{9}$" data-parsley-remote="{{ route('validate.mobile') }}" data-parsley-remote-validator='custom' data-parsley-remote-message="Mobile number already exists">

                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Present Address</label>
                                <textarea name="present_address" class="form-control" data-type="present" required>{{old('present_address')}}</textarea>
                            </div>
                            <div class="col-md-12">
                                <div class="row" id="present_address">
                                    <div class="col pe-0">
                                        <label>Pincode</label>
                                        <input type="text" name="present_pincode" class="form-control" autocomplete="off" value="{{ old('present_pincode') }}" data-type="present" required data-parsley-errors-container="#pincode1_error">
                                        <span class="text-danger pinerror" data-type="present">@error('present_pincode') {{ $message }} @enderror</span>
                                    </div>
                                    <div class="col-5 ps-0 pe-0">
                                        <label>State</label>
                                        <input type="text" name="present_state" class="form-control" readonly value="{{ old('present_state') }}" data-type="present">
                                        <span class="text-danger" data-type="present">@error('present_state') {{ $message }} @enderror</span>
                                    </div>
                                    <div class="col ps-0">
                                        <label>District</label>
                                        <input type="text" name="present_district" class="form-control" readonly value="{{ old('present_district') }}" data-type="present">
                                        <span class="text-danger" data-type="present">@error('present_district') {{ $message }} @enderror</span>
                                    </div>
                                    <div id="pincode1_error" class="text-danger"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                                <textarea class="form-control" name="permanent_address" data-type="permanent" required>{{old('permanent_address')}}</textarea>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col pe-0">
                                        <label>Pincode</label>
                                        <input type="text" name="permanent_pincode" class="form-control" autocomplete="off" value="{{ old('permanent_pincode') }}" data-type="permanent" required data-parsley-errors-container="#pincode2_error">
                                        <span class="text-danger pinerror" data-type="permanent">@error('permanent_pincode') {{ $message }} @enderror</span>
                                    </div>
                                    <div class="col-5 ps-0 pe-0">
                                        <label>State</label>
                                        <input type="text" name="permanent_state" class="form-control" readonly value="{{ old('permanent_state') }}" data-type="permanent">
                                        <span class="text-danger" data-type="permanent">@error('permanent_state') {{ $message }} @enderror</span>
                                    </div>
                                    <div class="col ps-0">
                                        <label>District</label>
                                        <input type="text" name="permanent_district" class="form-control" readonly value="{{ old('permanent_district') }}" data-type="permanent">
                                        <span class="text-danger" data-type="permanent">@error('permanent_district') {{ $message }} @enderror</span>
                                    </div>
                                    <div id="pincode2_error" class="text-danger"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>Date of Birth <span class="text-danger">*</span></label>
                       <input type="text" id="date_of_birth" name="date_of_birth" class="form-control date" placeholder="Select date.." readonly required data-parsley-age value="{{old('date_of_birth')}}">
                    </div>
                    <div class="col-md-6">
                        <label>Industry</label>
                        <select name="industry" class="form-control">
                            <option value="null">Select Industry</option>
                            @foreach($industries as $industry)
                            <option value="{{ $industry->value_description }}" {{ old('industry') == $industry->value_description ? 'selected' : '' }}>{{ $industry->value_description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="">
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
    @if(old('languages'))
        @foreach(old('languages') as $index => $oldLanguage)
            <tr class="language-row">
                <td>
                    <select name="languages[{{ $index }}][name]" class="form-select" required>
                        <option value="">Select Language</option>
                        @foreach($languages as $language)
                            <option value="{{ $language->value_description }}" {{ $oldLanguage['name'] == $language->value_description ? 'selected' : '' }}>
                                {{ $language->value_description }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <div class="row text-center pt-2">
                        <div class="col">
                            <label>
                                <input type="checkbox" name="languages[{{ $index }}][skills][]" value="Read"
                                    {{ is_array($oldLanguage['skills'] ?? null) && in_array('Read', $oldLanguage['skills']) ? 'checked' : '' }}
                                    data-parsley-multiple="skills-{{ $index }}" 
                                    data-parsley-required="true" 
                                    data-parsley-required-message="At least one skill must be selected"> 
                                Read
                            </label>
                        </div>
                        <div class="col border-start">
                            <label>
                                <input type="checkbox" name="languages[{{ $index }}][skills][]" value="Write"
                                    {{ is_array($oldLanguage['skills'] ?? null) && in_array('Write', $oldLanguage['skills']) ? 'checked' : '' }}
                                    data-parsley-multiple="skills-{{ $index }}"> 
                                Write
                            </label>
                        </div>
                        <div class="col border-start">
                            <label>
                                <input type="checkbox" name="languages[{{ $index }}][skills][]" value="Speak"
                                    {{ is_array($oldLanguage['skills'] ?? null) && in_array('Speak', $oldLanguage['skills']) ? 'checked' : '' }}
                                    data-parsley-multiple="skills-{{ $index }}"> 
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
    @else
        <tr class="language-row">
            <td>
                <select name="languages[0][name]" class="form-select" required>
                    <option value="">Select Language</option>
                    @foreach($languages as $language)
                        <option value="{{ $language->value_description }}">{{ $language->value_description }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <div class="row text-center pt-2">
                    <div class="col">
                        <label>
                            <input type="checkbox" name="languages[0][skills][]" value="Read"
                                data-parsley-multiple="skills-0" 
                                data-parsley-required="true" 
                                data-parsley-required-message="At least one skill must be selected"> 
                            Read
                        </label>
                    </div>
                    <div class="col border-start">
                        <label>
                            <input type="checkbox" name="languages[0][skills][]" value="Write" 
                                data-parsley-multiple="skills-0"> 
                            Write
                        </label>
                    </div>
                    <div class="col border-start">
                        <label>
                            <input type="checkbox" name="languages[0][skills][]" value="Speak"
                                data-parsley-multiple="skills-0"> 
                            Speak
                        </label>
                    </div>
                </div>
            </td>
            <td></td> <!-- No Remove button on the first row -->
        </tr>
    @endif
</tbody>

</table>


                </div>

                <hr>
                <div class="text-center text-muted mb-2 mt-4">----: Qualification Details :----</div>
                <div class="row mb-2">
                 <div class="col-md-12">
                    <table class="table table-sm table-bordered" id="qualificationTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Stream</th>
                                <th>Specialisation</th>
                                <th>Passing Year</th>
                                <th>University/Board/Institution</th>
                                <th>Marks Obtained</th>
                                <th>Grade/%/CGPA</th>
                                <th>Mode</th>
                                <th>
                                    <button type="button" id="addRowBtn" class="btn btn-sm btn-danger"><i class="fas fa-plus"></i></button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (old('stream'))
                            @foreach (old('stream') as $index => $stream)
                            <tr class="qualificationRow">
                                <td>
                                    <select name="stream[]" class="form-control">
                                        <option>Select Stream</option>
                                        @foreach($streams as $s)
                                        <option value="{{ $s->value_description }}" {{ old('stream.' . $index) == $s->value_description ? 'selected' : '' }}>{{ $s->value_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="specialisation[]" class="form-control">
                                        <option>Select Specialisation</option>
                                        @foreach($specialisations as $specialisation)
                                        <option value="{{ $specialisation->value_description }}" {{ old('specialisation.' . $index) == $specialisation->value_description ? 'selected' : '' }}>{{ $specialisation->value_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="year_of_passing[]" class="form-control">
                                        <option>Year</option>
                                        @for ($year = 1965; $year <= date('Y'); $year++)
                                        <option value="{{ $year }}" {{ old('year_of_passing.' . $index) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="institution[]" class="form-control" value="{{ old('institution.' . $index) }}">
                                </td>
                                <td>
                                    <input type="text" name="marks_obtained[]" class="form-control" value="{{ old('marks_obtained.' . $index) }}">
                                </td>
                                <td>
                                    <input type="text" name="grade[]" class="form-control" value="{{ old('grade.' . $index) }}">
                                </td>
                                <td>
                                    <select name="mode[]" class="form-control">
                                        <option>Education Mode</option>
                                        <option value="Part Time" {{ old('mode.' . $index) == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                        <option value="Full Time" {{ old('mode.' . $index) == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="Correspondence" {{ old('mode.' . $index) == 'Correspondence' ? 'selected' : '' }}>Correspondence</option>
                                        <option value="Online" {{ old('mode.' . $index) == 'Online' ? 'selected' : '' }}>Online</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger deleteRowBtn"><i class="fa-solid fa-xmark"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="qualificationRow">
                                <td>
                                    <select name="stream[]" class="form-control">
                                        <option>Select Stream</option>
                                        @foreach($streams as $stream)
                                        <option>{{$stream->value_description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="specialisation[]" class="form-control">
                                        <option>Select Specialisation</option>
                                        @foreach($specialisations as $specialisation)
                                        <option>{{$specialisation->value_description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="year_of_passing[]" class="form-control">
                                        <option>Year</option>
                                        <?php
                                        $currentYear = date("Y");
                                        for ($year = 1965; $year <= $currentYear; $year++) {
                                            echo "<option value=\"$year\">$year</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="institution[]" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="marks_obtained[]" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="grade[]" class="form-control">
                                </td>
                                <td>
                                    <select name="mode[]" class="form-control">
                                        <option>Education Mode</option>
                                        <option>Part Time</option>
                                        <option>Full Time</option>
                                        <option>Correspondence</option>
                                        <option>Online</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger deleteRowBtn" style="display: none;"><i class="fa-solid fa-xmark"></i></button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>



            <hr>
            <div class="text-center text-muted mb-2 mt-4">----: Work Experience :----</div>

            <table class="table table-sm table-bordered" id="employmentHistoryTable">
                <thead class="table-dark">
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Company</th>
                        <th>Designation</th>
                        <th>
                            <button type="button" id="addEmploymentRowBtn" class="btn btn-sm btn-danger"><i class="fas fa-plus"></i></button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (old('from'))
                    @foreach (old('from') as $index => $from)
                    <tr class="employmentRow">
                        <td>
                            <input type="month" name="from[]" class="form-control" value="{{ old('from.' . $index) }}">
                        </td>
                        <td>
                            <input type="month" name="to[]" class="form-control" value="{{ old('to.' . $index) }}">
                        </td>
                        <td>
                            <input type="text" name="company[]" class="form-control" value="{{ old('company.' . $index) }}">
                        </td>
                        <td>
                            <input type="text" name="designation[]" class="form-control" value="{{ old('designation.' . $index) }}">
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger deleteEmploymentRowBtn"><i class="fa-solid fa-xmark"></i></button>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr class="employmentRow">
                        <td>
                            <input type="month" name="from[]" class="form-control">
                        </td>
                        <td>
                            <input type="month" name="to[]" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="company[]" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="designation[]" class="form-control">
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger deleteEmploymentRowBtn" style="display: none;"><i class="fa-solid fa-xmark"></i></button>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="row mb-2">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Current CTC</label>
                            <input type="text" name="current_ctc" class="form-control" value="{{old('current_ctc')}}">
                        </div>
                        <div class="col-md-6">
                            <label>Expected CTC</label>
                            <input type="text" name="expected_ctc" class="form-control" value="{{old('expected_ctc')}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>Password <span class="text-danger">*</span></label>
                    <input id="password" type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                    data-parsley-pattern-message="Min 8 chars with uppercase, lowercase, number and special character." autocomplete="new-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label>Confirm Password <span class="text-danger">*</span></label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" data-parsley-equalto="#password">
                </div>
            </div>

            <div class="text-center text-muted mb-2 mt-4">----: Other :----</div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <label>Tags/Keywords/Key Skills</label>
                    <textarea class="form-control" name="tags" placeholder="">{{old('tags')}}</textarea>
                </div>
                <div class="col-md-6">
                    <label>Job Portal Reference (Add multiple in new line)</label>
                    <textarea class="form-control" name="portal_ref" placeholder="">{{old('portal_ref')}}</textarea>
                </div>
            </div>


            <div class="bg-light p-2">
                <div class="text-center text-muted mb-2 mt-4">----: Uploads :----</div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>CV Upload <span class="text-danger">*</span></label>
                        <input type="file" name="cv_upload" accept=".doc,.docx" class="form-control" data-parsley-filetype="doc, docx"
                        data-parsley-filetype-message="Only DOC and DOCX files are allowed." required>
                        @if (old('cv_upload_path'))
                        <p>Current CV: <a href="{{ asset(old('cv_upload_path')) }}" target="_blank">View CV</a></p>
                        @endif
                    </div>
                </div>
                <!-- <div class="row mb-2">
                    <div class="col-md-6">
                        <label>Photo Upload</label>
                        <div class="photo_preview">
                            <span>Image Preview</span>
                            <img src="{{ old('photo_path') ? asset(old('photo_path')) : '' }}" alt="Image Preview" id="photoPreview" style="max-width: 100%;">
                        </div>
                        <input type="file" name="photo" id="photo" class="form-control" data-parsley-filetype="jpeg, jpg, png"
                        data-parsley-filetype-message="Only JPEG, JPG, and PNG files are allowed.">
                    </div>
                    <div class="col-md-6">
                        <label>Signature Upload</label>
                        <div class="photo_preview">
                            <span>Image Preview</span>
                            <img src="{{ old('signature_path') ? asset(old('signature_path')) : '' }}" alt="Image Preview" id="signaturePreview" style="max-width: 100%;">
                        </div>
                        <input type="file" name="signature" class="form-control" id="signature" data-parsley-filetype="jpeg, jpg, png"
                        data-parsley-filetype-message="Only JPEG, JPG, and PNG files are allowed.">
                    </div>
                </div> -->

            </div>



            <div class="row mb-2 mt-2">
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
 
        <div class="row mb-0 mt-2">
            <div class="col-md-5 offset-md-5">
                <button type="submit" class="btn btn-danger">
                    {{ __('Register') }}
                </button>
				
            </div>
        </div>
			<p class="text-danger text-center">For support, please email on <b>support@tnmhr.com</b></p>
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
    //parsley validation
    $(document).ready(function() {
        $('#form').parsley();

         window.Parsley.addValidator('age', {
    validateString: function(value) {
        // Manually parse the date assuming format DD/MM/YYYY
        var parts = value.split('/');
        var dateOfBirth = new Date(parts[2], parts[1] - 1, parts[0]);

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

        window.Parsley.addValidator('filetype', {
            validateString: function(value, requirement, parsleyInstance) {
                var file = parsleyInstance.$element[0].files[0];
                if (!file) {
                    return true; // if no file selected, don't validate for file type
                }
                var allowedExtensions = requirement.split(',').map(function(ext) {
                    return ext.trim().toLowerCase();
                });
                var fileExtension = file.name.split('.').pop().toLowerCase();
                return allowedExtensions.indexOf(fileExtension) !== -1;
            },
            messages: {
                en: 'Invalid file type.'
            }
        });

        window.Parsley.addAsyncValidator('custom', function(xhr) {
            var response = JSON.parse(xhr.responseText);
            return response.valid === true;
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

        document.querySelector('.btn-refresh').addEventListener('click', function() {
            fetch("{{ route('refresh-captcha') }}")
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.captcha span').innerHTML = data.captcha;
                });
        });


        

          document.getElementById('add-language-btn').addEventListener('click', function () {
            var container = document.getElementById('languages-container');
            var rowCount = container.querySelectorAll('.language-row').length;

            // Collect all selected languages
            var selectedLanguages = Array.from(container.querySelectorAll('select[name^="languages"]')).map(function(select) {
                return select.value;
            }).filter(function(value) {
                return value !== "Select Language" && value !== "";
            });

            // Create a new row
            var newRow = document.createElement('tr');
            newRow.classList.add('language-row');

            // Build the language select options, excluding the selected ones
            var languageOptions = '<option>Select Language</option>';
            @foreach($languages as $language)
                if (!selectedLanguages.includes('{{ $language->value_description }}')) {
                    languageOptions += `<option value="{{ $language->value_description }}">{{ $language->value_description }}</option>`;
                }
            @endforeach

            // Set the new row's inner HTML
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
            container.appendChild(newRow);

            // Attach event listener to the remove button
            newRow.querySelector('.remove-language-btn').addEventListener('click', function () {
                newRow.remove();
            });
        });

        // Attach remove button event listener to existing rows if any
        document.querySelectorAll('.remove-language-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                this.closest('.language-row').remove();
            });
        });


        


        $('#addRowBtn').click(function() {
            var newRow = $('.qualificationRow').first().clone();
            newRow.find('input, select').val('');
            newRow.find('.deleteRowBtn').show();
            $('#qualificationTable tbody').append(newRow);
        });

        $('#qualificationTable').on('click', '.deleteRowBtn', function() {
            $(this).closest('tr').remove();
        });


        $('#addEmploymentRowBtn').click(function() {
            var newRow = $('.employmentRow').first().clone();
            newRow.find('input, select').val('');
            newRow.find('.deleteEmploymentRowBtn').show();
            $('#employmentHistoryTable tbody').append(newRow);
        });

    // Function to remove employment history rows
        $('#employmentHistoryTable').on('click', '.deleteEmploymentRowBtn', function() {
            $(this).closest('tr').remove();
        });
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
       /* const photoInput = document.getElementById('photo');
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
        }*/
    });

</script>
@endsection
