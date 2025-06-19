<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\LookupValue;
use App\Models\CandidateUser;
use App\Models\CandidateQualification;
use App\Models\CandidateExperience;
use App\Models\CandidateDocument;
use App\Models\Template;
use App\Models\CandidateLanguage;
use App\Helpers\EmailHelper;
use App\Helpers\SMSHelper;
use Carbon\Carbon;

class RegisterController extends Controller
{
    // Show the registration form

    public function showRegistrationForm(){
        
        return view('auth.register1');
    }

    public function showregistrationverification(Request $request){
        // return $request;
        // $request->validate([
        //     'email' => 'required|string|email|max:255|unique:mysql.candidate_users',
        //     'mobile' => 'required|string|max:10|unique:mysql.candidate_users',
        //     'captcha' => 'required|captcha',
        // ],
        // [
        //     'captcha.captcha' => 'The CAPTCHA is incorrect. Please try again.'
        // ]);
        
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'unique:mysql.candidate_users,email'
            ],
            'mobile' => [
                'required',
                'string',
                'regex:/^[6-9]\d{9}$/',
                'unique:mysql.candidate_users,mobile'
            ],
            'captcha' => 'required|captcha',
        ], [
            'email.regex' => 'Please enter a valid email address.',
            'mobile.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
            'captcha.captcha' => 'The CAPTCHA is incorrect. Please try again.'
        ]);

		$emailOtp = rand(100000, 999999);
        $mobileOtp = rand(100000, 999999);
		 
		// Save OTP to session
		session([
			'email' => $request->email,
			'mobile' => $request->mobile,
			'email_otp' => $emailOtp,
			'sms_otp' => $mobileOtp,
			// 'user_id' => $user->id,
		]);
		 
		
        
        // Send OTP via email and SMS
        EmailHelper::setMailConfig();
        $emailTemplate = Template::where('type', 'Email')
            ->where('name', 'OTP Email')
            ->where('status', 1)
            ->first();
        if ($emailTemplate) {
            $emailMessage = $emailTemplate->description;
            $emailMessage = str_replace('[name]', "Candidate", $emailMessage);
            $emailMessage = str_replace('[otp]', $emailOtp, $emailMessage);

            $email = $request->email;
            Mail::html($emailMessage, function ($msg) use ($email) {
                $msg->to($email)->subject('Verify email via OTP with TNMHR.');
            });
        }

        
        // Below code is use for the sms purpose 

          $smsTemplate = Template::where('type', 'SMS')
             ->where('name', 'OTP SMS')
             ->where('status', 1)
             ->first();
        
        if ($smsTemplate) {
			$smsMessage = strip_tags($smsTemplate->description);            
			$smsMessage = str_replace('[otp]', $mobileOtp, $smsMessage);
			$smsMessage = str_replace("&nbsp;", " ", $smsMessage); // Replace &nbsp; with space
			$smsMessage = html_entity_decode($smsMessage, ENT_QUOTES, 'UTF-8'); // Decode HTML entities
			$smsMessage = trim(preg_replace('/\s+/', ' ', $smsMessage));
			
			SMSHelper::setSMSConfig($request->mobile, $smsMessage);
			
		}
        
        
        return redirect()->route('verifyOtpForm')->with(
			'success', 'OTP sent to your Email .',
		);
    }

    public function showRegistrationForm0()
    {
        $genders=LookupValue::where('type_id',3)->get();
        $industries=LookupValue::where('type_id',4)->get();
        $streams=LookupValue::where('type_id',10)->get();  
        $specialisations=LookupValue::where('type_id',11)->get(); 
        $languages=LookupValue::where('type_id',28)->get(); 
		
        return view('auth.register',compact('genders','industries','streams','specialisations','languages'));
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        Auth::login($user);

        return redirect()->route('home');
    }

    public function validateMobile(Request $request)
    {
        $mobile = $request->query('mobile');
        $exists = CandidateUser::where('mobile', $mobile)->exists();
        
        return response()->json(['valid' => !$exists]);
    }
    public function validateEmail(Request $request)
    {
        $email = $request->query('email');
        $exists = CandidateUser::where('email', $email)->exists();
        
        return response()->json(['valid' => !$exists]);
    }

     public function store(Request $request)
    {


        $validatedData = $request->validate([
            // 'name' => 'required|string|max:255',
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'gender' => 'required|string',
            'email' => 'required|string|email|max:255|unique:mysql.candidate_users',
            'mobile' => 'required|string|max:10|unique:mysql.candidate_users',
            'present_address' => 'required|string',
            'present_pincode' => 'required|string',
            'present_state' => 'required|string',
            'present_district' => 'required|string',
            'permanent_address' => 'required|string',
            'permanent_pincode' => 'required|string',
            'permanent_state' => 'required|string',
            'permanent_district' => 'required|string',
            /*'date_of_birth' => 'required|date',*/
            'industry' => 'nullable|string',
            'password' => 'required|string|confirmed|min:8',
            'current_ctc' => 'nullable|string',
            'expected_ctc' => 'nullable|string',
            'tags' => 'nullable|string',
            'portal_ref' => 'nullable|string',
            'cv_upload' => 'required|file|mimes:doc,docx',
            'photo' => 'nullable|file|mimes:jpeg,jpg,png',
            'signature' => 'nullable|file|mimes:jpeg,jpg,png',
           /* 'captcha' => ['required', 'captcha']
        ],[
               'captcha.captcha' => 'The CAPTCHA is incorrect. Please try again.',*/
        ]);

        // Create the user
        $user = new CandidateUser();
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->present_address = $request->present_address;
        $user->present_pincode = $request->present_pincode;
        $user->present_state = $request->present_state;
        $user->present_district = $request->present_district;
        $user->permanent_address = $request->permanent_address;
        $user->permanent_pincode = $request->permanent_pincode;
        $user->permanent_state = $request->permanent_state;
        $user->permanent_district = $request->permanent_district;
        $user->date_of_birth =  Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
        $user->industry = $request->industry;
        $user->password = Hash::make($request->password);
        $user->current_ctc = $request->current_ctc;
        $user->expected_ctc = $request->expected_ctc;
        $user->tags = $request->tags;
        $user->portal_ref = $request->portal_ref;
        $user->email_verified_at = now();
        $user->mobile_verified_at = now();
		
         $user->save();

       // Store languages known
        if ($request->has('languages')) {
            foreach ($request->languages as $index => $languageData) {
                if (!empty($languageData['name'])) {  // Use 'name' instead of 'language'
                    $language = new CandidateLanguage();
                    $language->candidate_user_id = $user->id;
                    $language->language = $languageData['name']; // Use 'name' instead of 'language'
					$skills = $languageData['skills'] ?? [];
                    $language->read = in_array('Read', $languageData['skills']) ? 1 : 0;
                    $language->write = in_array('Write', $languageData['skills']) ? 1 : 0;
                    $language->speak = in_array('Speak', $languageData['skills']) ? 1 : 0;
                     $language->save();
                }
            }
        }

        /*
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $candidateDocument=new CandidateDocument();
            $candidateDocument->candidate_user_id=$user->id;
            $candidateDocument->document_type="photo";
            $candidateDocument->file_path = $photoPath;
            $candidateDocument->save();
        }

        if ($request->hasFile('signature')) {
            $signaturePath = $request->file('signature')->store('signatures', 'public');
            $candidateDocument=new CandidateDocument();
            $candidateDocument->candidate_user_id=$user->id;
            $candidateDocument->document_type="signature";
            $candidateDocument->file_path = $signaturePath;
            $candidateDocument->save();
        }*/

        

        // Store CV
         // Handle file uploads
        if ($request->hasFile('cv_upload')) {
            $cvPath = $request->file('cv_upload')->store('cv', 'public');
            $candidateDocument=new CandidateDocument();
            $candidateDocument->candidate_user_id=$user->id;
            $candidateDocument->document_type="cv";
            $candidateDocument->file_path = $cvPath;
             $candidateDocument->save();
        }
        

        // Store qualifications
       if ($request->has('stream')) {
        foreach ($request->stream as $index => $stream) {
            
                $qualification = new CandidateQualification();
                $qualification->candidate_user_id = $user->id;
                $qualification->stream = $stream;
                $qualification->specialisation = $request->specialisation[$index];
                $qualification->passing_year = $request->year_of_passing[$index];
                $qualification->institution = $request->institution[$index];
                $qualification->marks_obtained = $request->marks_obtained[$index];
                $qualification->grade = $request->grade[$index];
                $qualification->mode = $request->mode[$index];
                 $qualification->save();
            
        }
    }


        // Store experiences
        if ($request->from) {
            foreach ($request->from as $index => $from) {
                $experience = new CandidateExperience();
                $experience->candidate_user_id = $user->id;
                $experience->from_date = $from;
                $experience->to_date = $request->to[$index];
                $experience->company = $request->company[$index];
                $experience->designation = $request->designation[$index];
               $experience->save();
            }
        }
		 
        
        return redirect()->route('login')->with('success', 'Registration Done Successfully!');
    }

    public function showVerifyOtpForm()
    {
        return view('auth.verify_otp');
    }

    public function verifyOtp(Request $request)
    {

        if ($request->isMethod('get')) {
            return redirect()->route('register')->with('error', 'Network issue. Please Try Again');
        }
        
        $request->validate([
            'email_otp' => 'nullable|numeric',
            'sms_otp' => 'nullable|numeric',
        ]);


        $emailOtp = session('email_otp');
        $smsOtp = session('sms_otp');
        // $userId = session('user_id');
        $email = session('email');
    	$mobile = session('mobile');
        
        // $user = CandidateUser::findOrFail($userId);
        
       if ($request->email_otp == $emailOtp) {
       
         EmailHelper::setMailConfig();
        $emailTemplate = Template::where('type', 'Email')
            ->where('name', 'New Candidate Registration')
            ->where('status', 1)
            ->first();
		
        if ($emailTemplate) {
            $emailMessage = $emailTemplate->description;
            // $emailMessage = str_replace('[name]', $user->name, $emailMessage);
            $emailMessage = str_replace('[name]', "Candidate ", $emailMessage);
            // $emailMessage = str_replace('[regno]', $user->id, $emailMessage);
			
            // $email = $user->email;

            Mail::html($emailMessage, function ($msg) use ($email) {
                $msg->to($email)->subject('You are successfully register with TNMHR.');
            });
        }


        
        if($request->sms_otp == $smsOtp){

        // below code is use to sent the sms opt 
        
          $smsTemplate = Template::where('type', 'SMS')
             ->where('name', 'New Candidate Registration')
             ->where('status', 1)
             ->first();
         if ($smsTemplate) {
             $smsMessage = strip_tags($smsTemplate->description);    
            //  $smsMessage = str_replace('[regno]', $user->id, $smsMessage);
             $smsMessage = str_replace('[regno]', "Candidate :", $smsMessage);
            //  SMSHelper::setSMSConfig($user->mobile, $smsMessage);
             SMSHelper::setSMSConfig($mobile, $smsMessage);
         }

         return redirect()->route('register0')->with('success','OTP verified Successfully');

        }
        else{
            return redirect()->back()->withErrors(['otp' => 'Invalid. SMS OTPs must be correct.']);
        }
		
        // Clear the OTPs from session if both are verified
            // session()->forget(['email', 'mobile', 'email_otp', 'sms_otp', 'user_id']);

            // return redirect()->route('login')->with('success', 'OTP verified successfully!');
            // return redirect()->route('register0')->with('success','OTP verified Successfully');
        } else {
            return redirect()->back()->withErrors(['otp' => 'Invalid. Email OTPs must be correct.']);
        }
		
    }

 
    public function resendOtp()
    {

        // Generate new OTPs
        $emailOtp = rand(100000, 999999);
        $mobileOtp = rand(100000, 999999);
		$email = session('email');
    	$mobile = session('mobile');
		// $userId = session('user_id');
        
		// $user = CandidateUser::findOrFail($userId);
		
        // Send OTP via email
        EmailHelper::setMailConfig();
        $emailTemplate = Template::where('type', 'Email')
            ->where('name', 'OTP Email')
            ->where('status', 1)
            ->first();
        if ($emailTemplate) {
            $emailMessage = $emailTemplate->description;
            // $emailMessage = str_replace('[name]', $user->name, $emailMessage);
            $emailMessage = str_replace('[name]', "Candidate ", $emailMessage);
            $emailMessage = str_replace('[otp]', $emailOtp, $emailMessage);
			
			
            Mail::html($emailMessage, function ($msg) use ($email) {
                $msg->to($email)->subject('Verify email via OTP with TNMHR.');
            });
            
        }

        // Send OTP via SMS
         $smsTemplate = Template::where('type', 'SMS')
			->where('name', 'OTP SMS')
			->where('status', 1)
			->first();
		
		if ($smsTemplate) {
			$smsMessage = strip_tags($smsTemplate->description);            
			$smsMessage = str_replace('[otp]', $mobileOtp, $smsMessage);
			$smsMessage = str_replace("&nbsp;", " ", $smsMessage); // Replace &nbsp; with space
			$smsMessage = html_entity_decode($smsMessage, ENT_QUOTES, 'UTF-8'); // Decode HTML entities
			$smsMessage = trim(preg_replace('/\s+/', ' ', $smsMessage));
			
			SMSHelper::setSMSConfig($mobile, $smsMessage);
			
		}

        // Update OTPs in session
        session([
            'email_otp' => $emailOtp,
            'sms_otp' => $mobileOtp,
        ]);

        return back()->with('success', 'OTP resent to your email and mobile.');
    }


}