@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-danger text-white">{{ __('Application Submitted Successfully') }}</div>

                    <div class="card-body bg-white text-center">
                        <h4 class="mb-4">Thank you for your application!</h4>
                        
                        <div class="success-icon">
                            <i class="fas fa-check-circle fa-5x text-success"></i>
                        </div>

                        <p class="mt-4">Your application has been successfully submitted.</p>
                        
                        <a href="{{route('myapplication')}}" class="btn btn-danger">View Your Application</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .success-icon {
            animation: bounceIn 1s ease;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
@endsection
