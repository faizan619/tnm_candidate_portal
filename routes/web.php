<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContractualController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MyApplicationController;
use App\Http\Controllers\CandidateProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApplicationQuickController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\MyDocumentController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/job-openings',[HomeController::class,'jobOpenings'])->name('jobOpenings');
Route::post('/job-openings-filter',[HomeController::class,'FilterJobOpenings'])->name('FilterJobOpenings');

Route::get('/', function () {
    return view('auth.login');
});
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

/*Route::get('/refresh-captcha', function () {
    return response()->json(['captcha'=> captcha_img()]);
});
*/

Route::get('/refresh-captcha', [HomeController::class, 'refresh'])->name('refresh-captcha');


Route::get('/validate-email', [RegisterController::class, 'validateEmail'])->name('validate.email');
Route::get('/validate-mobile', [RegisterController::class,'validateMobile'])->name('validate.mobile');

Route::post('/register', [RegisterController::class, 'store'])->name('register');
Route::get('/verify-otp', [RegisterController::class, 'showVerifyOtpForm'])->name('verifyOtpForm');
// Route::post('/verified-otp', [RegisterController::class, 'verifyOtp'])->name('verifyOtp');
Route::match(['get', 'post'], '/verified-otp', [RegisterController::class, 'verifyOtp'])->name('verifyOtp');
Route::get('/resend-otp', [RegisterController::class, 'resendOtp'])->name('resendOtp');

Route::post('/verification',[RegisterController::class,'showregistrationverification'])->name('showregistrationverification');
Route::get('/register0',[RegisterController::class,'showRegistrationForm0'])->name('register0');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::middleware(['auth'])->group(function () 
{

    Route::get('/myprofile', [CandidateProfileController::class, 'index'])->name('myprofile');
    Route::get('/myprofile/personal_details/edit/{candidate_id}', [CandidateProfileController::class, 'personalDetailsEdit'])->name('myprofile.personal_details.edit');
    Route::post('/myprofile/personal_details/update', [CandidateProfileController::class, 'personalDetailsUpdate'])->name('myprofile.personal_details.update');
    Route::get('/myprofile/education/edit/{candidate_id}', [CandidateProfileController::class, 'educationEdit'])->name('myprofile.education.edit');
    Route::post('/myprofile/education/update', [CandidateProfileController::class, 'educationUpdate'])->name('myprofile.education.update');
    Route::get('/myprofile/experience/edit/{candidate_id}', [CandidateProfileController::class, 'experienceEdit'])->name('myprofile.experience.edit');
    Route::post('/myprofile/experience/update', [CandidateProfileController::class, 'experienceUpdate'])->name('myprofile.experience.update');
    Route::get('/myprofile/otherdetails/edit/{candidate_id}', [CandidateProfileController::class, 'otherDetailsEdit'])->name('myprofile.otherdetails.edit');
    Route::post('/myprofile/otherdetails/update', [CandidateProfileController::class, 'otherDetailsUpdate'])->name('myprofile.otherdetails.update');

    Route::get('/apply_for_job',  function () {
        return view('candidate.apply_for_job');
    })->name('apply_for_job');


  
    Route::get('/application/{requirement_id}/{position}/personal_details',[ContractualController::class, 'application_personal_details'])->name('application.personal_details');
    Route::get('/projects/search', [ContractualController::class, 'search'])->name('projects.search');

    Route::post('/application/personal_details/store', [ContractualController::class, 'application_personal_details_store'])->name('application.personal_details.store');
    Route::get('/application/education/{application_id}', [ContractualController::class, 'application_education'])->name('application.education');
    Route::post('/application/education', [ContractualController::class, 'application_education_store'])->name('application.education.store');

    Route::get('/application/experience/{application_id}', [ContractualController::class, 'application_experience'])->name('application.experience');

    Route::post('/application/experience', [ContractualController::class, 'application_experience_store'])->name('application.experience.store');
    Route::get('/application/upload/{application_id}', [ContractualController::class, 'application_upload'])->name('application.upload');
    Route::post('/application/upload', [ContractualController::class, 'application_upload_store'])->name('application.upload.store');
    Route::post('/application/finalsubmit/{application_id}',[ContractualController::class, 'finalsubmit'])->name('application.finalsubmit');

    

    Route::get('/application/preview/{application_id}', [ContractualController::class, 'applicationPreview'])->name('application.preview');


    Route::get('/myapplication', [MyApplicationController::class, 'index'])->name('myapplication');
    Route::get('/myapplication/details/{application_id}',[MyApplicationController::class,'myApplication_details'])->name('myapplication.details');

    Route::get('/mydocument', [MyDocumentController::class, 'index'])->name('mydocument');

    Route::get('password/change', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('password/change', [ChangePasswordController::class, 'changePassword'])->name('password.change.post');
});

Route::get('project-notifications/{project_id}',[ContractualController::class,'getNotifications']);
    

//Route::get('/fetch-state-district', [ContractualController::class, 'fetchStateDistrict'])->withoutMiddleware('auth');
Route::get('/fetch-state-district', [ContractualController::class, 'fetchStateDistrict'])->withoutMiddleware(['auth', 'verified'])
    ->name('fetch.state.district');

 Route::get('/contractuals', [ContractualController::class, 'index'])->name('current_opening');
    Route::post('/filter-projects', [ContractualController::class, 'filterProjects'])->name('filterProjects');
    Route::get('/contractuals/requirements/{requirement_id}/{position}',[ContractualController::class, 'getRequirement']);
    Route::get('/contractuals/requirements/{requirement_id}/{position}/share',[ContractualController::class, 'getShareRequirement']);
    Route::post('contractuals/share/email', [ContractualController::class, 'shareViaEmail'])->name('share.email');
    Route::post('contractuals/share/whatsapp', [ContractualController::class, 'shareViaWhatsApp'])->name('share.whatsapp');


Route::get('/application/quick/{requirement_id}/{position}',[ApplicationQuickController::class,'index'])->name('application_quick');
Route::post('/application/quick/store',[ApplicationQuickController::class,'store'])->name('application_quick.store');




Route::get('/application/success', function () {
        return view('contractuals.application.success');
    })->name('application.success');


Route::get('/application/download/{application_id}',[MyApplicationController::class,'applicationDownload'])->name('application.download');




Route::get('/run-storage-link', function () {
        Artisan::call('storage:link');
        return "The storage link has been created.";
});

Route::get('/clear-cache', function () {
    // Clear route cache
    Artisan::call('route:clear');
    // Clear config cache
    Artisan::call('config:clear');
    // Clear application cache
    Artisan::call('cache:clear');
    // Clear compiled view files
    Artisan::call('view:clear');
    
    return "Cache cleared!";
});

Route::get('/run-artisan-publish', function () {
    $exitCode = Artisan::call('vendor:publish', [
        '--provider' => 'Barryvdh\DomPDF\ServiceProvider',
    ]);

    // Optionally return the exit code or a success message
    return response()->json([
        'message' => 'Command executed successfully',
        'exit_code' => $exitCode,
    ]);
});