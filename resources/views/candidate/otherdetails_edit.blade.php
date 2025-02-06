@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-8">
            <h4>My Profile / Other Details Edit</h4>
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
                   <form method="POST" action="{{ route('myprofile.otherdetails.update') }}" id="form" enctype="multipart/form-data">
                    @csrf 

                     <div class="row mb-2">
                
                        <div class="col-md-6">
                            <label>Current CTC</label>
                            <input type="text" name="current_ctc" class="form-control" value="{{old('current_ctc',$candidate->current_ctc)}}">
                        </div>
                        <div class="col-md-6">
                            <label>Expected CTC</label>
                            <input type="text" name="expected_ctc" class="form-control" value="{{old('expected_ctc',$candidate->expected_ctc)}}">
                        </div>
                    
               
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <label>Tags/Keywords/Key Skills</label>
                    <textarea class="form-control" name="tags" placeholder="Account, Manager">{{old('tags',$candidate->tags)}}</textarea>
                </div>
                <div class="col-md-6">
                    <label>Job Portal Reference (Add multiple in new line)</label>
                    <textarea class="form-control" name="portal_ref" placeholder="https://www.linkedin.com/in/tnmhr/ \n https://www.naukri.com/mnjuser/profile?id=&tnmhr">{{old('portal_ref',$candidate->portal_ref)}}</textarea>
                </div>
            </div>


            <div class="bg-light p-2">
                <div class="text-center text-muted mb-2 mt-4">----: Uploads :----</div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>CV Upload</label>
                        <input type="file" name="cv_upload" accept=".doc,.docx" class="form-control" data-parsley-filetype="doc, docx"
                        data-parsley-filetype-message="Only DOC and DOCX files are allowed.">
                        @if (old('cv_upload_path'))
                        <p>Current CV: <a href="{{ asset(old('cv_upload_path')) }}" target="_blank">View CV</a></p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">Last updated at {{ \Carbon\Carbon::parse($candidateCV->created_at)->format('d/m/Y') }}</span>
                        <a href="{{asset('storage/'.$candidateCV->cv_upload)}}" class="btn btn-sm btn-danger">Dowunload your CV <i class="fas fa-download"></i></a> 
                    </div>
                </div>
                

            </div>

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
@endsection

@section('script')
<script src="{{ asset('js/fetch_pincode.js') }}"></script>
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

   
</script>
@endsection
