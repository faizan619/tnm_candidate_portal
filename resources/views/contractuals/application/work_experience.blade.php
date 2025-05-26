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

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-danger text-white">
                    <div class="row">
                        <div class="col-8">{{ __('Application Form') }} (Work Experience)</div>
                        <div class="col text-end">
                            <a href="{{ route('application.education', ['application_id' => $application->id]) }}" class="btn btn-danger">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" id="application-form" enctype="multipart/form-data">
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

                        <div class="text-center text-muted mb-2">----: Work Experience :----</div>

                        <div id="workexperience-container">
                            @foreach ($workExperienceData as $key => $experience)
                                <div class="workexperience-block mb-4">
                                    <div class="row bg-grey p-2 pb-4 rounded">
                                        <div class="col-md-4 mb-2">
                                            <label for="from">From <span class="text-danger">*</span></label>
                                            <input type="text" name="from[]" class="form-control date" placeholder="Select date.." value="{{ \Carbon\Carbon::parse($experience->from_date)->format('d/m/Y') }}" readonly required>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="to">To <span class="text-danger">*</span></label>
                                            <input type="text" name="to[]" class="form-control date" placeholder="Select date.." value="{{ \Carbon\Carbon::parse($experience->to_date)->format('d/m/Y') }}" readonly data-parsley-dateorder required>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="company">Company <span class="text-danger">*</span></label>
                                            <input type="text" name="company[]" class="form-control" value="{{ $experience->company }}" required maxlength="20">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="designation">Designation <span class="text-danger">*</span></label>
                                            <input type="text" name="designation[]" class="form-control" value="{{ $experience->designation }}" maxlength="20" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="work_type">Work Type <span class="text-danger">*</span></label>
                                            <select name="work_type[]" class="form-control" required>
                                                <option disabled selected>Select work type</option>
                                                <option value="Part Time" {{ $experience->work_type == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                                <option value="Full Time" {{ $experience->work_type == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                                <option value="Work From Home" {{ $experience->work_type == 'Work From Home' ? 'selected' : '' }}>Work From Home</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="ctc">CTC <span class="text-danger">*</span></label>
                                            <input type="number" name="ctc[]" class="form-control" value="{{ $experience->ctc }}" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="expctc">Expected CTC</label>
                                            <input type="number" name="expctc[]" id="expctc" class="form-control" value="{{ $experience->expctc }}" required>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="responsibilities">Responsibilities</label>
                                            <textarea name="responsibilities[]" class="form-control">{{ $experience->responsibilities }}</textarea>
                                        </div>
                                    </div>
                                    <div class="text-center mb-2" style="margin-top:-30px">
                                        <button type="button" class="btn btn-lg btn-grey rounded-circle add-more" title="Add More"><i class="fa-solid fa-plus"></i></button>
                                        <button type="button" class="btn btn-lg btn-grey rounded-circle remove-block" title="Remove Block" style="{{ $loop->first ? 'display: none;' : '' }}"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                </div>
                            @endforeach

                            @if ($workExperienceData->isEmpty())
                                <div class="workexperience-block  mb-4">
                                    <input type="hidden" name="experience_id[]" value="">
                                    <div class="row bg-grey p-2 pb-4 rounded">
                                        <div class="col-md-4 mb-2">
                                            <label for="from">From <span class="text-danger">*</span></label>
                                            <input type="text" name="from[]" class="form-control date" placeholder="Select date.." value="{{ old('from.0') }}" autocomplete="off" readonly required>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="to">To <span class="text-danger">*</span></label>
                                            <input type="text" name="to[]" class="form-control date" placeholder="Select date.." value="{{ old('to.0') }}" autocomplete="off" readonly data-parsley-dateorder required>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="company">Company <span class="text-danger">*</span></label>
                                            <input type="text" name="company[]" class="form-control" required maxlength="20">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="designation">Designation <span class="text-danger">*</span></label>
                                            <input type="text" name="designation[]" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="work_type">Work Type <span class="text-danger">*</span></label>
                                            <select name="work_type[]" class="form-control" required>
                                                <option>Select work type</option>
                                                <option>Part Time</option>
                                                <option>Full Time</option>
                                                <option>Work From Home</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="ctc">CTC <span class="text-danger">*</span></label>
                                            <input type="number" name="ctc[]" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="expctc">Expected CTC</label>
                                            <input type="number" name="expctc[]" id="expctc" class="form-control" required>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="responsibilities">Responsibilities</label>
                                            <textarea name="responsibilities[]" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="text-center mb-2" style="margin-top: -30px">
                                        <button type="button" class="btn btn-lg btn-grey rounded-circle add-more" title="Add More"><i class="fa-solid fa-plus"></i></button>
                                        <button type="button" class="btn btn-lg btn-grey rounded-circle remove-block" title="Remove Block" style="display: none;"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>Total Experience</label>
                                <div class="input-group mb-3">
                                    <input type="text" id="total_experience" name="total_experience" class="form-control text-danger" readonly value="0">
                                    <span class="input-group-text text-muted" id="basic-addon2">Years</span>
                                </div>
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
                                <span class="captcha-error text-danger" role="alert"></span>
                            </div>
                        </div> -->

                        <div class="row mb-0 mt-4">
                            <div class="col text-center">
                                <a href="{{ route('application.education', ['application_id' => $application->id]) }}" class="btn btn-danger">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                                <button type="button" id="submit-form" class="btn btn-danger">
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
    $(document).ready(function () {
        $('#application-form').parsley();

        /*document.querySelector('.btn-refresh').addEventListener('click', function() {
        fetch("{{ route('refresh-captcha') }}")
            .then(response => response.json())
            .then(data => {
                document.querySelector('.captcha span').innerHTML = data.captcha;
            });
    });*/

        $('.date').flatpickr({
            dateFormat: 'd/m/Y',
            allowInput: true,
            maxDate: "today"
        });
        
        window.Parsley.addValidator('dateorder', {
        validateString: function (value, requirement, parsleyInstance) {
            var $block = parsleyInstance.$element.closest('.workexperience-block');
            var fromDateValue = $block.find('input[name="from[]"]').val();
            if (fromDateValue) {
                var fromDateParts = fromDateValue.split('/');
                var fromDate = new Date(fromDateParts[2], fromDateParts[1] - 1, fromDateParts[0]);
                var toDateParts = value.split('/');
                var toDate = new Date(toDateParts[2], toDateParts[1] - 1, toDateParts[0]);
                return toDate > fromDate;
            }
            return true;
        },
        messages: {
            en: 'Invalid date'
        }
    });

        function calculateTotalExperience() {
            var totalExperience = 0;
            $('#workexperience-container .workexperience-block').each(function() {
                var fromDate = $(this).find('input[name="from[]"]').val();
                var toDate = $(this).find('input[name="to[]"]').val();
                if (fromDate && toDate) {
                    fromDate = fromDate.split('/');
                    toDate = toDate.split('/');
                    var from = new Date(fromDate[2], fromDate[1] - 1, fromDate[0]);
                    var to = new Date(toDate[2], toDate[1] - 1, toDate[0]);
                    var experience = (to - from) / (1000 * 60 * 60 * 24 * 365);
                    totalExperience += experience;
                }
            });
            $('#total_experience').val(totalExperience.toFixed(1));
        }

        $('#workexperience-container').on('change', 'input[name="from[]"], input[name="to[]"]', function () {
            calculateTotalExperience();
        });

        $('#workexperience-container').on('click', '.add-more', function () {
            var newBlock = $('.workexperience-block').first().clone();

            newBlock.find('input, select, textarea').val('');
            newBlock.find('input[name="experience_id[]"]').val('');

            newBlock.find('.date').flatpickr({
                dateFormat: 'd/m/Y',
                allowInput: true
            });

            newBlock.find('.remove-block').show();
            newBlock.find('.add-more').show();

            $('#workexperience-container').append(newBlock);
        });

        $('#workexperience-container').on('click', '.remove-block', function () {
            $(this).closest('.workexperience-block').remove();
            calculateTotalExperience();
        });

        $('#submit-form').click(function () {
            var isValid = $('#application-form').parsley().validate();
            if (!isValid) {
                return;
            }

            var formData = $('#application-form').serialize();

            $.ajax({
                url: "{{ route('application.experience.store') }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        $('.alert-success').html(response.message).show();
                        setTimeout(function() {
                           window.location.href = "{{ url('/application/upload') }}/" + response.application_id;
                        }, 100);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.hasOwnProperty('captcha')) {
                            $('#captcha').addClass('is-invalid');
                            $('.captcha-error').html('<strong>' + errors.captcha[0] + '</strong>');
                        } else {
                            $('.captcha-error').removeClass('is-invalid');
                            $('.captcha-error').html('The CAPTCHA is incorrect. Please try again.');
                        }
                    } else {
                        console.error('Error occurred:', xhr.responseText);
                    }
                }
            });
        });

        calculateTotalExperience();
    });
</script>
@endsection
