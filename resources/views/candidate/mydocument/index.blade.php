@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h3 class="text-muted"><b>My Documents</b></h3>
            <div class="card card-body bg-white">
                @if($candidateDocument->isEmpty())
                    <p>No documents found.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>File Path</th>
                                <th>Uploaded At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($candidateDocument as $document)
                                <tr>
                                    <td>{{ $document->document_type }}</td>
                                    <td><a href="#" class="view-document" data-bs-toggle="modal" data-bs-target="#documentModal" data-file="{{ asset('storage/' . $document->file_path) }}">View Document</a></td>
                                    <td>{{ $document->created_at->format('d M Y h:i:s A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Document Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="documentFrame" src="" width="100%" height="500px"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var documentModal = document.getElementById('documentModal');
        documentModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var fileSrc = button.getAttribute('data-file');
            var iframe = documentModal.querySelector('#documentFrame');
            iframe.src = fileSrc;
        });

        documentModal.addEventListener('hidden.bs.modal', function () {
            var iframe = documentModal.querySelector('#documentFrame');
            iframe.src = '';
        });
    });
</script>
@endsection
