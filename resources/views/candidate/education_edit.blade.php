@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <h4>My Profile / Education Edit</h4>
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
                   <form method="POST" action="{{ route('myprofile.education.update') }}" id="form" enctype="multipart/form-data">
                    @csrf 
                     
                    <div class="row mb-2">
                 <div class="col-md-12">
                    <table class="table table-sm table-bordered" id="qualificationTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Stream</th>
                                <th>Specialisation</th>
                                <th>Passing Year</th>
                                <th>University/Board/Institution</th>
                                <th>Marks Obtained</th>
                                <th>Grade/%/CGPA</th>
                                <th>Mode</th>
                                <th>
                                    <button type="button" id="addRowBtn" class="btn btn-sm btn-danger"><i class="fas fa-plus"></i></button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (old('stream', $qualifications))
                                @foreach (old('stream', $qualifications) as $index => $stream)
                                <tr class="qualificationRow">
                                    <td>
                                        <select name="stream[]" class="form-control">
                                            <option>Select Stream</option>
                                            @foreach($streams as $s)
                                            <option value="{{ $s->value_description }}" {{ (old('stream.' . $index, $qualifications[$index]->stream ?? '') == $s->value_description) ? 'selected' : '' }}>{{ $s->value_description }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="specialisation[]" class="form-control">
                                            <option>Select Specialisation</option>
                                            @foreach($specialisations as $specialisation)
                                            <option value="{{ $specialisation->value_description }}" {{ (old('specialisation.' . $index, $qualifications[$index]->specialisation ?? '') == $specialisation->value_description) ? 'selected' : '' }}>{{ $specialisation->value_description }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="year_of_passing[]" class="form-control">
                                            <option>Year</option>
                                            @for ($year = 1965; $year <= date('Y'); $year++)
                                            <option value="{{ $year }}" {{ old('year_of_passing.' . $index, $qualifications[$index]->passing_year ?? '') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="institution[]" class="form-control" value="{{ old('institution.' . $index, $qualifications[$index]->institution ?? '') }}">
                                    </td>
                                    <td>
                                        <input type="text" name="marks_obtained[]" class="form-control" value="{{ old('marks_obtained.' . $index, $qualifications[$index]->marks_obtained ?? '') }}">
                                    </td>
                                    <td>
                                        <input type="text" name="grade[]" class="form-control" value="{{ old('grade.' . $index, $qualifications[$index]->grade ?? '') }}">
                                    </td>
                                    <td>
                                        <select name="mode[]" class="form-control">
                                            <option>Education Mode</option>
                                            <option value="Part Time" {{ old('mode.' . $index, $qualifications[$index]->mode ?? '') == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                            <option value="Full Time" {{ old('mode.' . $index, $qualifications[$index]->mode ?? '') == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                            <option value="Correspondence" {{ old('mode.' . $index, $qualifications[$index]->mode ?? '') == 'Correspondence' ? 'selected' : '' }}>Correspondence</option>
                                            <option value="Online" {{ old('mode.' . $index, $qualifications[$index]->mode ?? '') == 'Online' ? 'selected' : '' }}>Online</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger deleteRowBtn"><i class="fa-solid fa-xmark"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr class="qualificationRow">
                                    <td>
                                        <select name="stream[]" class="form-control">
                                            <option>Select Stream</option>
                                            @foreach($streams as $stream)
                                            <option>{{$stream->value_description}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="specialisation[]" class="form-control">
                                            <option>Select Specialisation</option>
                                            @foreach($specialisations as $specialisation)
                                            <option>{{$specialisation->value_description}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="year_of_passing[]" class="form-control">
                                            <option>Year</option>
                                            @for ($year = 1965; $year <= date('Y'); $year++)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="institution[]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="marks_obtained[]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="grade[]" class="form-control">
                                    </td>
                                    <td>
                                        <select name="mode[]" class="form-control">
                                            <option>Education Mode</option>
                                            <option>Part Time</option>
                                            <option>Full Time</option>
                                            <option>Correspondence</option>
                                            <option>Online</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger deleteRowBtn" style="display: none;"><i class="fa-solid fa-xmark"></i></button>
                                    </td>
                                </tr>
                            @endif

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
</div>
@endsection
@section('script')
<script src="{{asset('js/fetch_pincode.js')}}"></script>
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

    $('#addRowBtn').click(function() {
            var newRow = $('.qualificationRow').first().clone();
            newRow.find('input, select').val('');
            newRow.find('.deleteRowBtn').show();
            $('#qualificationTable tbody').append(newRow);
        });

        $('#qualificationTable').on('click', '.deleteRowBtn', function() {
            $(this).closest('tr').remove();
        });


       
    
</script>
@endsection