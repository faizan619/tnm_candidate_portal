    @extends('layouts.app')

    @section('content')
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

                <!-- Displaying success messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <div class="row">
                            <div class="col-8">{{ __('Application Form') }} (Education Details)</div>
                            <div class="col text-end">
                                <a href="{{ route('application.personal_details', ['requirement_id' => $application->requirement_id, 'position' => $application->post_applied_for]) }}" class="btn btn-danger">
                                    <i class="fa-solid fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('application.education.store') }}" id="form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="application_id" value="{{ $application->id }}">
                            <input type="hidden" name="requirement_id" value="{{ $application->requirement_id }}">

                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label for="position">Post Applied for <span class="text-danger">*</span></label>
                                    <input type="text" name="position" class="form-control" value="{{ $application->post_applied_for }}" disabled required>
                                </div>
                                <div class="col-md-8">
                                    <label for="location">Locations <span class="text-danger">*</span></label>
                                    <div>
                                         @php
                                            $locations = json_decode($application->locations, true);
                                        @endphp

                                         @foreach ($locations as $location)
                                            <span class="badge bg-danger">{{ $location }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="text-center text-muted mb-2">----: Education Details :----</div>

                            <div id="education-container">
                                @if($educationRecords->isEmpty())
                                    <div class="education-block bg-grey rounded mb-4">
                                        <h4 class="text-center pt-2">Minimum Qualification</h4>
                                        <input type="hidden" name="education_id[]" value="">
                                        <div class="row p-2 pb-4">
                                            <div class="col-md-4 mb-2">
                                                <label for="stream">Stream</label>
                                                <select name="stream[]" class="form-control" required>
                                                    <option selected disabled value="">Select stream</option>
                                                    @foreach ($qualifications->pluck('level1')->unique()->filter() as $level1)
                                                    <option value="{{ $level1 }}">{{ $level1 }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="year_of_passing">Year of passing </label>
                                                <select name="year_of_passing[]" class="form-control" required>
                                                    <option selected disabled value="">Select year</option>
                                                    @for ($year = date('Y'); $year >= 1965; $year--)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="university_board_institution">University/Board/Institution </label>
                                                <input type="text" name="university_board_institution[]" class="form-control" placeholder="" required value="">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="grade">Grade / % / CGPA </label>
                                                <input type="text" name="grade[]" class="form-control" placeholder="" required value="">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="mode">Mode </label>
                                                <select name="mode[]" class="form-control" required>
                                                    <option selected disabled value="">Select education mode</option>
                                                    <option value="Part Time">Part Time</option>
                                                    <option value="Full Time">Full Time</option>
                                                    <option value="Online">Online</option>
                                                    <option value="Correspondence">Correspondence</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @foreach ($educationRecords as $key => $record)
                                    <div class="education-block bg-grey rounded mb-4">
                                        @if($key < 1)
                                            <h4 class="text-center pt-2">Minimum Qualification</h4>
                                        @else
                                            <h4 class="text-center pt-2">Additional Qualification</h4>
                                        @endif
                                        <input type="hidden" name="education_id[]" value="{{ $record->id }}">
                                        <div class="row p-2 pb-4">
                                            <div class="col-md-4 mb-2">
                                                <label for="stream">Stream</label>
                                                <select name="stream[]" class="form-control" required>
                                                    <option selected disabled value="">Select stream</option>
                                                    @foreach ($qualifications->pluck('level1')->unique()->filter() as $level1)
                                                    <option value="{{ $level1 }}" {{ $record->stream == $level1 ? 'selected' : '' }}>{{ $level1 }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="year_of_passing">Year of passing </label>
                                                <select name="year_of_passing[]" class="form-control" required>
                                                    <option selected disabled value="">Select year</option>
                                                    @for ($year = date('Y'); $year >= 1965; $year--)
                                                    <option value="{{ $year }}" {{ $record->year_of_passing == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="university_board_institution">University/Board/Institution </label>
                                                <input type="text" name="university_board_institution[]" class="form-control" placeholder="" required value="{{ $record->university_institutions }}">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="grade">Grade / % / CGPA </label>
                                                <input type="text" name="grade[]" class="form-control" placeholder="" required value="{{ $record->grade }}">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="mode">Mode </label>
                                                <select name="mode[]" class="form-control" required>
                                                    <option selected disabled value="">Select education mode</option>
                                                    <option value="Part Time" {{ $record->mode == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                                    <option value="Full Time" {{ $record->mode == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                                    <option value="Online" {{ $record->mode == 'Online' ? 'selected' : '' }}>Online</option>
                                                    <option value="Correspondence" {{ $record->mode == 'Correspondence' ? 'selected' : '' }}>Correspondence</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="text-center text-muted mb-2">----: Certification Details :----</div>
                            <div id="certification-container">
    @if($certificationRecords->isNotEmpty())
        @foreach ($certificationRecords as $key => $certification)
            <div class="certification-block">
                <div class="bg-light p-2">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="certification_number">Certification Number:</label>
                            <input type="text" class="form-control" name="certification_number[]" id="certification_number" value="{{ $certification->certification_number }}">
                        </div>
                        <!-- @foreach(old('certification_number', []) as $index => $value)
                            <div class="col-md-4 mb-2">
                                <label for="certification_number_{{ $index }}">Certification Number {{ $index + 1 }}:</label>
                                <input type="text" class="form-control" name="certification_number[]" id="certification_number_{{ $index }}" value="{{ $value }}">
                            </div>
                        @endforeach -->
                        <div class="col-md-4 mb-2">
                            <label>Course</label>
                            <input type="text" name="course[]" class="form-control" value="{{ $certification->course }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label>Subject</label>
                            <input type="text" name="subject[]" class="form-control" value="{{ $certification->subject }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label>Expire Date</label>
                            <input type="date" name="expire[]" class="form-control" value="{{ $certification->expire }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label>Percentage %</label>
                            <input type="text" name="percentage[]" class="form-control" value="{{ $certification->percentage }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label>Passing Year</label>
                            <select name="cer_passing_year[]" class="form-control">
                                <option>Select Year</option>
                                @php
                                    $currentYear = date('Y');
                                    $startYear = 1900;
                                @endphp
                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                    <option value="{{ $year }}" {{ $certification->passing_year == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label>Institute</label>
                            <input type="text" name="cer_institute[]" class="form-control" value="{{ $certification->institute }}">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Duration</label>
                            <input type="text" name="duration[]" class="form-control" value="{{ $certification->duration }}">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Mode</label>
                            <select name="cer_mode[]" class="form-control">
                                <option selected disabled value="">Select education mode</option>
                                <option value="Part Time" {{ $certification->mode == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                <option value="Full Time" {{ $certification->mode == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                <option value="Online" {{ $certification->mode == 'Online' ? 'selected' : '' }}>Online</option>
                                <option value="Correspondence" {{ $certification->mode == 'Correspondence' ? 'selected' : '' }}>Correspondence</option>
                            </select>
                        </div>
                        <div class="mb-4"></div>
                    </div>
                </div>
                <div class="text-center mb-2" style="margin-top: -30px">
                    <button type="button" class="btn btn-lg btn-grey rounded-circle add-more" title="Add More"><i class="fa-solid fa-plus"></i></button>
                    <button type="button" class="btn btn-lg btn-grey rounded-circle remove-block" title="Remove Block" style="display: none;"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        @endforeach
    @else
        <!-- Display an empty block only if there are no existing records -->
        <div class="certification-block">
            <div class="bg-light p-2">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label for="certification_number">Certification Number:</label>
                        <input type="text" class="form-control" name="certification_number[]" id="certification_number" value="{{ old('certification_number.0') }}">
                    </div>
                    <!-- @foreach(old('certification_number', []) as $index => $value)
                        <div class="col-md-4 mb-2">
                            <label for="certification_number_{{ $index }}">Certification Number {{ $index + 1 }}:</label>
                            <input type="text" class="form-control" name="certification_number[]" id="certification_number_{{ $index }}" value="{{ $value }}">
                        </div>
                    @endforeach -->

                    <div class="col-md-4 mb-2">
                        <label>Course</label>
                        <input type="text" name="course[]" class="form-control">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label>Subject</label>
                        <input type="text" name="subject[]" class="form-control">
                    </div>
                     <div class="col-md-2 mb-2">
                            <label>Expire Date</label>
                            <input type="date" name="expire[]" class="form-control" value="{{ $certification->expire }}">
                        </div>
                    <div class="col-md-4 mb-2">
                        <label>Percentage %</label>
                        <input type="text" name="percentage[]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label>Passing Year</label>
                        <select name="cer_passing_year[]" class="form-control">
                            <option>Select Year</option>
                            @php
                                $currentYear = date('Y');
                                $startYear = 1900;
                            @endphp
                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label>Institute</label>
                        <input type="text" name="cer_institute[]" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label>Duration</label>
                        <input type="text" name="duration[]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label>Mode</label>
                        <select name="cer_mode[]" class="form-control">
                            <option selected disabled value="">Select education mode</option>
                            <option value="Part Time">Part Time</option>
                            <option value="Full Time">Full Time</option>
                            <option value="Online">Online</option>
                            <option value="Correspondence">Correspondence</option>
                        </select>
                    </div>
                    <div class="mb-4"></div>
                </div>
            </div>
            <div class="text-center mb-2" style="margin-top: -30px">
                <button type="button" class="btn btn-lg btn-grey rounded-circle add-more" title="Add More"><i class="fa-solid fa-plus"></i></button>
                <button type="button" class="btn btn-lg btn-grey rounded-circle remove-block" title="Remove Block" style="display: none;"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    @endif
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
                            </div> -->

                            <div class="row mb-0 mt-4">
                                <div class="col-md-3 m-auto text-center">
                                    <a href="{{ route('application.personal_details', ['requirement_id' => $application->requirement_id, 'position' => $application->post_applied_for]) }}" class="btn btn-danger">
                                    <i class="fa-solid fa-arrow-left"></i> Back</a>
                                    

                                    <button type="submit" class="btn btn-danger">
                                        {{ __('Save & Next') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-8 mx-auto text-center mt-2">
                                <p>For any queries, please contact at <a href="mailto:{{$mailid}}">{{$mailid}}</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('script')
    <script src="{{ asset('js/fetch_pincode.js') }}"></script>
    <script src="{{asset('js/parsley.min.js')}}"></script>
    <script>
        $('#form').parsley();

        /*document.querySelector('.btn-refresh').addEventListener('click', function() {
        fetch("{{ route('refresh-captcha') }}")
            .then(response => response.json())
            .then(data => {
                document.querySelector('.captcha span').innerHTML = data.captcha;
            });
    });*/
      
      $('#certification-container').on('click', '.add-more', function () {
            var newBlock = $('.certification-block').first().clone();

            newBlock.find('input, select, textarea').val('');
            newBlock.find('input[name="experience_id[]"]').val('');

            newBlock.find('.date').flatpickr({
                dateFormat: 'd/m/Y',
                allowInput: true
            });

            newBlock.find('.remove-block').show();
            newBlock.find('.add-more').show();

            $('#certification-container').append(newBlock);
        });

        $('#certification-container').on('click', '.remove-block', function () {
            $(this).closest('.certification-block').remove();
            calculateTotalExperience();
        });

    </script>
    @endsection
