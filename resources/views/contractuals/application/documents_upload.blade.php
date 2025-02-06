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
                        <div class="col-8">{{ __('Application Form') }} (Documents Upload)</div>
                        <div class="col text-end">
                            <a href="{{ route('application.experience', ['application_id' => $application->id]) }}" class="btn btn-danger">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('application.upload.store') }}" id="form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="application_id" value="{{ $application->id }}">
                        <input type="hidden" name="requirement_id" value="{{ $application->requirement_id }}">

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="position">Post Applied for <span class="text-danger">*</span></label>
                                <input type="text" name="position" class="form-control" value="{{ $application->post_applied_for }}" disabled required>
                            </div>
                            <div class="col-md-4">
                                <label for="location">Locations <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" value="{{ $application->locations }}" disabled required>
                            </div>
                        </div>

                        <div class="text-center text-muted mb-2">----: Document Uploads :----</div>
                        
                        <div class="row bg-grey p-2 rounded">
                            @foreach($documentUpload as $index => $upload)
                            <div class="col-md-4 mb-2">
                                <label>{{ $upload }} <span class="text-danger">*</span></label>
                                <input type="file" name="upload[{{ $index }}]" class="form-control" required data-parsley-filemimetypes="application/pdf,image/jpeg" data-parsley-trigger="change" data-parsley-errors-container="#error-file-{{ $index }}">
                                <input type="hidden" name="document_type[{{ $index }}]" value="{{ $upload }}">
                                <div id="error-file-{{ $index }}"></div>
                            </div>
                            @endforeach

                            @if($requirements->age_proof_mandatory=='Yes')
                            <div class="col-md-4 mb-2">
                                <label>Age Proof Document <span class="text-danger">*</span></label>
                                <input type="file" name="upload[age_proof]" class="form-control" required data-parsley-filemimetypes="application/pdf,image/jpeg" data-parsley-trigger="change" data-parsley-errors-container="#error-file-age-proof">
                                <input type="hidden" name="document_type[age_proof]" value="Age Proof">
                                <div id="error-file-age-proof"></div>
                            </div>
                            @endif

                            @if($application->physically_challenged=='Yes')
                            <div class="col-md-4 mb-2">
                                <label>Disability Certificate <span class="text-danger">*</span></label>
                                <input type="file" name="upload[disability_certificate]" class="form-control" required data-parsley-filemimetypes="application/pdf,image/jpeg" data-parsley-trigger="change" data-parsley-errors-container="#error-file-disability">
                                <input type="hidden" name="document_type[disability_certificate]" value="Disability Certificate">
                                <div id="error-file-disability"></div>
                            </div>
                            @endif
                        </div>

                        <div class="row mt-4 p-2">
                            <div class="col-md-6">
                                <label>Photo</label>
                                <div class="bg-grey text-center" style="height: 200px; line-height: 200px;">
                                    @php
                                        $photo = $applicationDocuments->where('document_type', 'photo')->first();
                                    @endphp
                                    @if($photo)
                                        <img id="photoPreview" src="{{ asset('storage/' . $photo->document_file) }}" class="img-thumbnail rounded" style="width: 200px; height: 200px;">
                                    @else
                                        <img id="photoPreview" src="#" class="img-thumbnail rounded" style="width: 200px; height: 200px; display: none;">
                                        <p id="noPhoto" style="line-height: 200px;">No photo available</p>
                                    @endif
                                </div>
                                <input type="file" name="photo" class="form-control" onchange="previewImage(event, 'photoPreview', 'noPhoto')" data-parsley-filemimetypes="image/jpeg,image/png" data-parsley-trigger="change" data-parsley-errors-container="#error-photo">
                                <div id="error-photo"></div>
                            </div>
                            <div class="col-md-6">
                                <label>Signature</label>
                                <div class="bg-grey text-center" style="height: 200px; line-height: 200px;">
                                    @php
                                        $signature = $applicationDocuments->where('document_type', 'signature')->first();
                                    @endphp
                                    @if($signature)
                                        <img id="signaturePreview" src="{{ asset('storage/' . $signature->document_file) }}" class="img-thumbnail" style="width: 200px; height: 200px;">
                                    @else
                                        <img id="signaturePreview" src="#" class="img-thumbnail" style="width: 200px; height: 200px; display: none;">
                                        <p id="noSignature" style="line-height: 200px;">No signature available</p>
                                    @endif
                                </div>
                                <input type="file" name="signature" class="form-control" onchange="previewImage(event, 'signaturePreview', 'noSignature')" data-parsley-filemimetypes="image/jpeg,image/png" data-parsley-trigger="change" data-parsley-errors-container="#error-signature">
                                <div id="error-signature"></div>
                            </div>
                        </div>

                        <div class="row bg-dark text-white p-2 mt-4">
                            <div class="col-md-4">
                                <label>Your CV</label>
                                <div>
                                    @php
                                        $cvDocument = $applicationDocuments->where('document_type', 'cv')->first();
                                    @endphp

                                    @if ($cvDocument)
                                        <a href="{{ asset('storage/' . $cvDocument->document_file) }}" class="btn btn-danger btn-sm" download>
                                            Download Your Last Updated CV <i class="fas fa-download ml-1"></i>
                                        </a>
                                    @else
                                        <span>No CV uploaded</span>
                                    @endif
                                </div>
                                <div>
                                    @if ($cvDocument)
                                        Last Uploaded CV on {{ \Carbon\Carbon::parse($cvDocument->created_at)->format('d/m/Y') }}
                                    @else
                                        Last Uploaded CV on N/A
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
    <label>Upload your updated CV</label>
    <input type="file" name="cv_upload" class="form-control" 
        data-parsley-filemimetypes="application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document" 
        data-parsley-trigger="change"
        data-parsley-errors-container="#error-cv"
        data-parsley-error-message="Please upload a valid CV in PDF or DOCX format.">
    <div id="error-cv"></div>
</div>

                        </div>

                        <div class="row mb-0 mt-4">
                            <div class="col text-center">
                                <a href="{{ route('application.experience', ['application_id' => $application->id]) }}" class="btn btn-danger">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Submit') }}
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
<script src="{{ asset('js/fetch_pincode.js') }}"></script>
<script src="{{asset('js/parsley.min.js')}}"></script>
<script>
    window.Parsley.addValidator('filemimetypes', {
        validateString: function(value, requirement, parsleyInstance) {
            var file = parsleyInstance.$element[0].files[0];
            if (file) {
                var allowedTypes = requirement.split(',');
                if (allowedTypes.indexOf(file.type) !== -1) {
                    return true;
                }
            }
            return false;
        },
        messages: {
            en: 'Invalid file type.'
        }
    });

    function previewImage(event, previewId, noPreviewTextId) {
        var output = document.getElementById(previewId);
        var noPreviewText = document.getElementById(noPreviewTextId);
        output.src = URL.createObjectURL(event.target.files[0]);
        output.style.display = 'block';
        noPreviewText.style.display = 'none';
    }

    $('#form').parsley();
</script>
@endsection
