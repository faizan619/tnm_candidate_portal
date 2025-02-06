@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <h4>My Profile / Experience Edit</h4>
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
                   <form method="POST" action="{{ route('myprofile.experience.update') }}" id="form" enctype="multipart/form-data">
                    @csrf 

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered" id="employmentHistoryTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Company</th>
                                        <th>Designation</th>
                                        <th>
                                            <button type="button" id="addEmploymentRowBtn" class="btn btn-sm btn-danger"><i class="fas fa-plus"></i></button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($experiences as $index => $experience)
                                    <tr class="employmentRow">
                                        <input type="hidden" name="experience_id[]" value="{{ $experience->id }}">
                                        <td>
                                            <input type="month" name="from[]" class="form-control" value="{{ old('from.' . $index, $experience->from_date) }}">
                                        </td>
                                        <td>
                                            <input type="month" name="to[]" class="form-control" value="{{ old('to.' . $index, $experience->to_date) }}">
                                        </td>
                                        <td>
                                            <input type="text" name="company[]" class="form-control" value="{{ old('company.' . $index, $experience->company) }}">
                                        </td>
                                        <td>
                                            <input type="text" name="designation[]" class="form-control" value="{{ old('designation.' . $index, $experience->designation) }}">
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger deleteEmploymentRowBtn"><i class="fa-solid fa-xmark"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

    $('#addEmploymentRowBtn').click(function() {
        var newRow = $('.employmentRow').first().clone();
        newRow.find('input, select').val('');
        newRow.find('.deleteEmploymentRowBtn').show();
        $('#employmentHistoryTable tbody').append(newRow);
    });

    $('#employmentHistoryTable').on('click', '.deleteEmploymentRowBtn', function() {
        $(this).closest('tr').remove();
    });
</script>
@endsection
