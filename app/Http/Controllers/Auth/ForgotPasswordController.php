<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use App\Helpers\EmailHelper;



class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        // Set the email configuration dynamically
        EmailHelper::setMailConfig();

         $this->validateEmail($request);
    
		// Attempt to send the password reset email
		$response = $this->broker()->sendResetLink(
			$this->credentials($request)
		);

		// Redirect to login page with a success message
		if ($response == Password::RESET_LINK_SENT) {
			 return redirect()->route('login')->with('success', 'New password link sent to your email address.');
		} else {
			return $this->sendResetLinkFailedResponse($request, $response);
		}
    }
}
