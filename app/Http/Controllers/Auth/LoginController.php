<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        $login = request()->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        request()->merge([$field => $login]);
        return $field;
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ],
        [
            'captcha.captcha' => 'The CAPTCHA is incorrect. Please try again.'
        ]);

    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'login' => [trans('auth.failed')],
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        
        if ($user->email_verified_at && $user->mobile_verified_at) {
            // Retrieve the intended URL from the request or fallback to default path
            $intendedUrl = $request->input('intended_url', $this->redirectPath());
            // Redirect to the intended URL or the default path
            if ($intendedUrl) {
                return redirect()->intended($intendedUrl);
            } else {
                return redirect()->route('home'); // Replace 'home' with your home route name
            }
        } else {
            // Logout the user
            auth()->logout();

            return back()->withErrors(['error' => 'Your email or mobile is not verified. Please verify and try again. <a href="' . route('verifyOtp') . '">Verify Now</a>']);
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
}
