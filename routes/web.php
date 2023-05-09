<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuth;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\SurveyController;
use App\Http\Controllers\admin\CruiseController;
use App\Http\Controllers\admin\DestinationController;
use App\Http\Controllers\admin\AdventureController;

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

// ------------------------- Admin Login ------------------------------
Route::get('/',[AdminController::class,'login'])->name('admin/login');
Route::get('admin',[AdminController::class,'login'])->name('admin/login');
Route::post('admin/check-login',[AdminController::class,'check_login'])->name('admin/check_login');
Route::post('admin/logout',[AdminController::class,'logout'])->name('admin/logout');

Route::get('admin/forgot-password',[AdminController::class,'forgot_password'])->name('admin/forgot_password');
Route::post('send-password-reset-otp',[AdminController::class,'sendPasswordResetOtp'])->name('admin/sendPasswordResetOtp');
Route::get('admin/reset-password',[AdminController::class,'reset_password'])->name('admin/reset_password');
Route::post('admin/do-reset-password',[AdminController::class,'doResetPassword'])->name('admin/doResetPassword');

Route::group(['prefix' => 'admin', 'middleware' => AdminAuth::class], function () {
    Route::get('dashboard',[AdminController::class,'dashboard'])->name('admin/dashboard');

    Route::get('profile',[AdminController::class,'profile'])->name('admin/profile');
    Route::post('update-profile',[AdminController::class,'updateProfile'])->name('admin/updateProfile');

    Route::get('change-password',[AdminController::class,'changePassword'])->name('admin/changePassword');
    Route::post('update-password',[AdminController::class,'updatePassword'])->name('admin/updatePassword');

    Route::post('change-status/{id}/{status}/{table}/{wherecol}/{statusvariable}',[AdminController::class,'change_status'])->name('admin/change_status');

    /* Users Start */
    Route::get('users',[UserController::class,'users'])->name('admin/users');
    Route::get('user-details/{user_id}',[UserController::class,'userDetails'])->name('admin/userDetails');

    Route::post('notification',[UserController::class,'notification'])->name('admin/notification');
    Route::post('send-notification',[UserController::class,'sendNotification'])->name('admin/sendNotification');
    /* Users End */

    /* Surveys Start */
    Route::get('surveys',[SurveyController::class,'surveys'])->name('admin/surveys');
    Route::get('survey-details/{survey_id}',[SurveyController::class,'surveyDetails'])->name('admin/surveyDetails');
    /* Surveys End */

    /* Cruise Start */
    Route::get('cruise',[CruiseController::class,'cruise'])->name('admin/cruise');
    Route::any('cruise-form/{id?}',[CruiseController::class,'cruiseForm'])->name('admin/cruiseForm');
    Route::post('add-cruise',[CruiseController::class,'addCruise'])->name('admin/addCruise');
    Route::post('update-cruise/{id}',[CruiseController::class,'updateCruise'])->name('admin/updateCruise');
    Route::post('delete-cruise/{id}',[CruiseController::class,'deleteCruise'])->name('admin/deleteCruise');
    /* Cruise End */

    /* Destinations Start */
    Route::get('destinations',[DestinationController::class,'destinations'])->name('admin/destinations');
    Route::any('destination-form/{id?}',[DestinationController::class,'destinationForm'])->name('admin/destinationForm');
    Route::post('add-destination',[DestinationController::class,'addDestination'])->name('admin/addDestination');
    Route::post('update-destination/{id}',[DestinationController::class,'updateDestination'])->name('admin/updateDestination');
    Route::post('delete-destination/{id}',[DestinationController::class,'deleteDestination'])->name('admin/deleteDestination');
    /* Destinations End */

    /* Adventure Start */
    Route::get('adventures',[AdventureController::class,'adventures'])->name('admin/adventures');
    Route::any('adventure-form/{id?}',[AdventureController::class,'adventureForm'])->name('admin/adventureForm');
    Route::post('add-adventure',[AdventureController::class,'addAdventure'])->name('admin/addAdventure');
    Route::post('update-adventure/{id}',[AdventureController::class,'updateAdventure'])->name('admin/updateAdventure');
    Route::post('delete-adventure/{id}',[AdventureController::class,'deleteAdventure'])->name('admin/deleteAdventure');
    /* Adventure End */

    /* Site Setting Start */
    Route::get('site-setting/{key}',[AdminController::class,'siteSetting'])->name('admin/siteSetting');
    Route::get('site-setting/{key}',[AdminController::class,'siteSetting'])->name('admin/siteSetting');
    Route::get('site-setting/{key}',[AdminController::class,'siteSetting'])->name('admin/siteSetting');
    Route::post('update-site-setting',[AdminController::class,'updateSiteSetting'])->name('admin/updateSiteSetting');
    /* Site Setting End */

    /* FAQ Start */
    Route::get('faqs',[AdminController::class,'faqs'])->name('admin/faqs');
    Route::any('open-faq-form',[AdminController::class,'openFaqForm'])->name('admin/openFaqForm');
    Route::post('add-faq',[AdminController::class,'addFaq'])->name('admin/addFaq');
    Route::post('update-faq/{faq_id}',[AdminController::class,'updateFaq'])->name('admin/updateFaq');
    Route::post('delete-faq/{faq_id}',[AdminController::class,'deleteFaq'])->name('admin/deleteFaq');
    /* FAQ End */

    /* Social Start */
    Route::get('social',[AdminController::class,'social'])->name('admin/social');
    Route::post('update-social-link',[AdminController::class,'update_social_link'])->name('admin/update-social-link');
    /* Social End */

    /* Contact Start */
    Route::get('contact-details',[AdminController::class,'contactDetails'])->name('admin/contactDetails');
    Route::post('open-contact-form',[AdminController::class,'openContactForm'])->name('admin/openContactForm');
    Route::post('update-contact-details/{contact_detail_id}',[AdminController::class,'updateContactDetails'])->name('admin/updateContactDetails');
    /* Contact End */

    Route::fallback(function () {
        $data['admin_detail'] = admin_detail();
        return response()->view('errors.admin-404', $data, 404);
    });

});
