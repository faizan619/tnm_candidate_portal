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
                <div class="card-header bg-danger text-white">
                    <div class="row">
                        <div class="col-8">{{ __('Application Form') }}</div>
                        <div class="col text-end">
                            <a href="{{ url()->previous() }}" class="btn btn-sm btn-light"><i class="fa-solid fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white">
                    
                    <form method="POST" action="{{ route('application.education.store') }}" id="form" enctype="multipart/form-data">
                        @csrf
                         <input type="hidden" name="application_id" value="{{$application->id}}">
                         <input type="hidden" name="requirement_id" value="{{$application->requirement_id}}">
                         <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="position">Post Applied for <span class="text-danger">*</span></label>
                                <input type="text" name="position" class="form-control" value="{{$application->post_applied_for}}" disabled required>
                            </div>
                            <div class="col-md-4">
                                <label for="location">Locations <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" value="{{$application->locations}}" disabled required>
                            </div>
                        </div>

                        <div class="text-center text-muted mb-2">----: Education Details :----</div>
                        
                        <div id="education-container">
    <!-- Initial education block -->
    <div class="education-block  text-white mb-4 ">
        <div class="row bg-secondary p-2 pb-4 rounded">
        <div class="col-md-4 mb-2">
            <label for="stream">Stream <span class="text-danger">*</span></label>
            <select name="stream[]" class="form-control" required>
                <option disabled>Select your stream</option>
                <option value="1" {{ old('stream.0') == '1' ? 'selected' : '' }}>Stream Option 1</option>
                <option value="2" {{ old('stream.0') == '2' ? 'selected' : '' }}>Stream Option 2</option>
                <!-- Add more options as needed -->
            </select>

        </div>
        <div class="col-md-4 mb-2">
            <label for="substream">Substream</label>
            <select name="substream[]" class="form-control">
                <option selected disabled>Select your substream</option>
                <!-- Add options here -->
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <label for="year_of_passing">Year of passing <span class="text-danger">*</span></label>
            <select name="year_of_passing[]" class="form-control" required data-parsley-required-message="Please select a year">
                <option value="" selected disabled>Select year</option>
                @for ($year = date('Y'); $year >= 1900; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>

        </div>
        <div class="col-md-4 mb-2">
            <label for="university_board_institution">University/Board/Institution <span class="text-danger">*</span></label>
            <input type="text" name="university_board_institution[]" class="form-control" placeholder="" required>
        </div>
        <div class="col-md-4 mb-2">
            <label for="grade">Grade / % / CGPA <span class="text-danger">*</span></label>
            <input type="text" name="grade[]" class="form-control" placeholder="" required>
        </div>
        <div class="col-md-4 mb-2">
            <label for="mode">Mode <span class="text-danger">*</span></label>
           <select name="mode[]" class="form-control" required data-parsley-required-message="Please select an education mode">
                <option value="" selected disabled>Select education mode</option>
                <option value="Part Time">Part Time</option>
                <option value="Full Time">Full Time</option>
                <option value="Online">Online</option>
                <option value="Correspondence">Correspondence</option>
            </select>

        </div>
        
    </div>

        <div class="text-center mb-2" style="margin-top:-30px">
            <button type="button" class="btn btn-lg btn-secondary rounded-circle add-more" title="Add More"><i class="fa-solid fa-plus"></i></button>
            <button type="button" class="btn btn-lg btn-secondary rounded-circle remove-block" title="Remove Block" style="display: none;"><i class="fa-solid fa-xmark"></i></button>
        </div>
</div>       
</div>          
            

            <div class="row mb-2">
             <div class="col-md-4">
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
            <div class="col-md-5 offset-md-5">
                <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa-solid fa-arrow-left"></i> Back</a>
                <button type="submit" class="btn btn-danger">
                    {{ __('Save & Next') }}
                </button>
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
<script src="{{asset('js/fetch_pincode.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
<script>
    $('#form').parsley();

    

$(document).ready(function () {
    document.querySelector('.btn-refresh').addEventListener('click', function(){
        fetch('/refresh-captcha')
        .then(response => response.json())
        .then(data => {
            document.querySelector('.captcha span').innerHTML = data.captcha;
        });
    });

    // Add education block
    $('#education-container').on('click', '.add-more', function () {
        var newBlock = $('.education-block').first().clone(); // Clone the first block

        // Clear input values in the new block
        newBlock.find('input, select').val('');

        // Show remove button and hide add button in the new block
        //newBlock.find('.add-more').hide();
        newBlock.find('.remove-block').show();

        // Append the new block to the container
        $('#education-container').append(newBlock);
    });

    // Remove education block
    $('#education-container').on('click', '.remove-block', function () {
        $(this).closest('.education-block').remove();
    });

    // Initially hide remove button on the first block
    $('.education-block').first().find('.remove-block').hide();
});
</script>
@endsection
