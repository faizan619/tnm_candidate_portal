<!DOCTYPE html>
<html>
<head>
    <title>Application Details</title>
     <?php ini_set('max_execution_time', 300); ?>
     
    <style>
        @page {
            margin: 0; /* Adjust as needed */
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin:auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

        }
        .bg-secondary{
            background-color: grey;
            text-align: center;
        }
            

        .mt-4 {
            margin-top: 16px;
        }

        .mr-4 {
            margin-right: 16px;
        }
        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 5px;
            text-align: left;
            border: 1px solid #dee2e6;

        }
        thead{
            background-color: grey;
            color:white;
        }

        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
    </style>
   

</head>
<body>
    <div class="container">
        <div class="bg-secondary">
              <img src="{{ public_path('img/logo.jpg') }}" alt="T&M Logo" width="120"> 
             <!--  <img src="{{asset('img/logo.jpg')}}" alt="T&M Logo" width="120"> -->
        </div>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:20px;">
            <tr>
                <td width="30%" >
                <table class="table">
                    <tr>
                        <th>Position</th>
                        <td>{{$applicationPersonal->post_applied_for}}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>@if($applicationPersonal->mask_name_contact==0){{ucwords($applicationPersonal->name)}}@endif</td>
                    </tr>
                    <tr>
                        <th>Father's Name</th>
                        <td>{{ucwords($applicationPersonal->father_name)}}</td>
                    </tr>
                     <tr>
                        <th>Email</th>
                        <td>@if($applicationPersonal->mask_name_contact==0){{$applicationPersonal->email}}@endif</td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td>@if($applicationPersonal->mask_name_contact==0){{$applicationPersonal->mobile}}@endif</td>
                    </tr>
                   
                    <tr>
                        <th>{{ucwords($applicationPersonal->id_proof_type)}}</th>
                        <td>{{ucwords($applicationPersonal->id_proof_no)}}</td>
                    </tr>
                    <tr>
                        <th>DOB</th>
                        <td>{{ \Carbon\Carbon::parse($applicationPersonal->date_of_birth)->format('d/m/Y') }}</td>

                    </tr>
                    <tr>
                        <th>Place of Birth</th>
                        <td>{{$applicationPersonal->place_of_birth}}</td>
                    </tr>
                     <tr>
                        <th>Gender</th>
                        <td>{{$applicationPersonal->gender}}</td>
                    </tr>
                </table>
                </td>
        

            <td width="44%" >
                 <table class="table" cellpadding="0" cellspacing="0" border="0">
                    @php
                        $preferredLocations = json_decode($applicationPersonal->locations, true);
                    @endphp

                    @if(!empty($preferredLocations) && is_array($preferredLocations))
                        @foreach($preferredLocations as $index => $location)
                        <tr>
                            <th width="80">Preferred Location {{ $index + 1 }}</th>
                            <td>{{ $location }}</td>
                        </tr>
                        @endforeach
                    @endif

                    <tr>
                        <th>Religion</th>
                        <td>{{$applicationPersonal->religion}}</td>
                    </tr>
                    <tr >
                        <th>Caste Category</th>
                        <td>{{$applicationPersonal->caste}}</td>
                    </tr>
                    <tr>
                        <th>Marital Status</th>
                        <td>{{$applicationPersonal->marital_status}}</td>
                    </tr>
                    <tr>
                        <th>Present Address</th>
                        <td>{{$applicationPersonal->present_address}}, {{$applicationPersonal->present_district}}, {{$applicationPersonal->present_state}}, {{$applicationPersonal->present_pincode}}</td>
                    </tr>
                    <tr>
                        <th>Permanent Address</th>
                        <td>{{$applicationPersonal->permanent_address}}, {{$applicationPersonal->permanent_district}}, {{$applicationPersonal->permanent_state}}, {{$applicationPersonal->permanent_pincode}}</td>
                    </tr>
                    <tr>
                        <th>Annual CTC</th>
                        <td>{{$currentCTC->ctc}}</td>
                    </tr>
                   
                </table>
            </td>

            <td width="25%">
                @php
                    $photoDocument = $applicationDocument->where('document_type', 'photo')->first();
                @endphp
                    <div class="text-center">

                        @if($photoDocument && $photoDocument->document_file)
                           <!--  <img src="{{ asset('storage/' . $photoDocument->document_file) }}" alt="Candidate Photo" style="max-width: 150px; max-height: 150px;" class="img-thumbnail"> -->
                            <img src="{{ public_path('storage/' . $photoDocument->document_file) }}" alt="Candidate Photo" style="max-width: 150px; max-height: 150px;" class="img-thumbnail">
                        @else
                            No photo available.
                        @endif
                   </div>
                   <br><br>
                   <table class="table" cellpadding="0" cellspacing="0" border="0">
                       <!-- <tr>
                           <th>Present City</th>
                           <td>{{$applicationPersonal->present_district}}</td>
                       </tr>
                       <tr>
                           <th>Present State</th>
                           <td>{{$applicationPersonal->present_state}}</td>
                       </tr>
                        <tr>
                           <th>Permanent City</th>
                           <td>{{$applicationPersonal->permanent_district}}</td>
                       </tr>
                       <tr>
                           <th>Permanent State</th>
                           <td>{{$applicationPersonal->permanent_state}}</td>
                       </tr> -->
                        <tr>
                           <th>Apply Date</th>
                            <td>{{ Carbon\Carbon::parse($applicationPersonal->created_at)->format('d/m/Y H:i:s') }}</td>
                       </tr>

                   </table>
            </td>
        </table>

        <h4>Education Details:</h4>
        <div class="">
            <table class="table">
                <thead>
                <tr>
                    <th>Stream</th>
                    <th>Substream</th>
                    <th>Grade / % / CGPA</th>
                    <th>Passing Year</th>
                    <th>University/Board/Institution</th>
                    <th>Mode</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applicationEducation as $education)
                <tr>
                    <td>{{$education->stream}}</td>
                    <td>{{$education->substream}}</td>
                    <td>{{$education->grade}}</td>
                    <td>{{$education->year_of_passing}}</td>
                    <td>{{$education->university_institutions}}</td>
                    <td>{{$education->mode}}</td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>

         <h4>Certification Details:</h4>
        <div class="">
            <table class="table table-sm table-bordered">
                <thead class="bg-dark text-white">
                <tr>
                    <th>Course</th>
                    <th>Subject</th>
                    <th>Percentage %</th>
                    <th>Passing Year</th>
                    <th>Institute</th>
                    <th>Duration</th>
                    <th>Mode</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applicationCertification as $certificate)
                <tr>
                    <td>{{$certificate->course}}</td>
                    <td>{{$certificate->subject}}</td>
                    <td>{{$certificate->percentage}}</td>
                    <td>{{$certificate->passing_year}}</td>
                    <td>{{$certificate->institute}}</td>
                    <td>{{$certificate->duration}}</td>
                    <td>{{$certificate->mode}}</td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
         <h4>Experience Details:</h4>
        <div class="">
            <table class="table table-sm table-bordered">
                <thead class="bg-dark text-white">
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Organisation</th>
                    <th>Post </th>
                    <th>Brief Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applicationExperience as $experience)
                <tr>
                    <td>{{$experience->from_date}}</td>
                    <td>{{$experience->to_date}}</td>
                    <td>{{$experience->company}}</td>
                    <td>{{$experience->designation}}</td>
                    <td>{{$experience->responsibilities}}</td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>

        
           <table>
            <tr>
                <td class="table-cell" width="70%">
                    <h4>Documents Details:</h4>
                    @foreach($applicationDocument as $document)
                        @if($document->document_type !== 'photo' && $document->document_type !== 'signature')
                            <a href="{{ asset('storage/' . $document->document_file) }}" target="_blank" style="text-decoration: underline;" class="mr-4">
                                View {{ ucfirst($document->document_type) }}
                            </a>
                        @endif
                    @endforeach
                </td>
                <td class="table-cell text-right" width="30%">
                    @php
                        $signatureDocument = $applicationDocument->firstWhere('document_type', 'signature');
                    @endphp
                    @if($signatureDocument)
                        <img src="{{ public_path('storage/' . $signatureDocument->document_file) }}" alt="signature" width="120" style="margin-left:350px">
                    @endif
                </td>
            </tr>
        </table>
        </div>

        
</body>
</html>
