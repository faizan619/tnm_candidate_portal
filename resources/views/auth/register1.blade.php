@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
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
                <div class="card-header bg-danger text-white">{{ __('Registration') }}</div>
                <div class="card-body bg-white">
                    <form action="{{ route('showregistrationverification') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Email Address <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" data-parsley-remote="{{ route('validate.email') }}" data-parsley-remote-validator='custom' data-parsley-remote-message="Email already exists">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label>Mobile Number <span class="text-danger">*</span></label>
                                <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile" data-parsley-pattern="^[789]\d{9}$" data-parsley-remote="{{ route('validate.mobile') }}" data-parsley-remote-validator='custom' data-parsley-remote-message="Mobile number already exists">
                            </div>
                            <div class="col-md-6 mt-2 m-auto">
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
                            <div class="col-md-5 mt-2 offset-md-5">
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Sent OTP') }}
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
<script>
    document.querySelector('.btn-refresh').addEventListener('click', function() {
        fetch("{{ route('refresh-captcha') }}")
            .then(response => response.json())
            .then(data => {
                document.querySelector('.captcha span').innerHTML = data.captcha;
            });
    });
</script>
@endsection