<!-- resources/views/auth/verifyOtpForm.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">{{ __('Verify OTP') }}</div>

                <div class="card-body bg-white">
                    <!-- Displaying errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Displaying success messages -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
					
                        <!-- EMail: {{session('email_otp')}}<br>
                        Mobile: {{session('sms_otp')}}<br>
                        User: {{session('candidate_user_id')}}<br> -->
                       

                    <form method="POST" action="{{ route('verifyOtp') }}">
                        @csrf
					
                        <div class="form-group row mb-2">
                            <label for="email_otp" class="col-md-4 col-form-label text-md-right">{{ __('Email OTP') }}</label>

                            <div class="col-md-6">
                                <input id="email_otp" type="text" class="form-control @error('email_otp') is-invalid @enderror" name="email_otp" value="{{ old('email_otp') }}" required autofocus>

                                @error('email_otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        

                        <input type="hidden" name="sms_otp" id="sms_otp" value="{{ session('sms_otp') }}"> 

                        <div class="form-group row mb-2">
                            <label for="sms_otp" class="col-md-4 col-form-label text-md-right">{{ __('SMS OTP') }}</label>

                            <div class="col-md-6">
                                <input id="sms_otp" type="text" class="form-control @error('sms_otp') is-invalid @enderror" name="sms_otp" value="{{ old('sms_otp') }}" required>

                                @error('sms_otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> 

                        <div class="form-group row mb-2">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Verify OTP') }}
                                </button>
                                <a href="{{ route('resendOtp') }}" class="btn btn-link">{{ __('Resend OTP') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
