@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h3 class="text-muted"><b>{{ __('Apply For a Job') }}</b></h3>
                <div class="row">
                   
                    <div class="col"> 
                        <div class="card bg-white">
                            <div class="card-body">
                                <div class="btn-group">
                               <a href="{{url('contractuals')}}" class="btn btn-lg btn-danger border"><i class="fa-solid fa-briefcase"></i> Contractual</a>
                               <a href="" class="btn btn-lg btn-danger border"><i class="fa-solid fa-file-circle-check"></i> Permanent</a>
                           </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

