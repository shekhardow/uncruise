<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuth;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\SurveyController;
use App\Http\Controllers\admin\ShipController;
use App\Http\Controllers\admin\DestinationController;
use App\Http\Controllers\admin\ActivityController;
use App\Http\Controllers\admin\AdventureController;
use App\Http\Controllers\admin\ReviewController;

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
    Route::get('ships',[ShipController::class,'ships'])->name('admin/ships');
    Route::any('ship-form/{id?}',[ShipController::class,'shipForm'])->name('admin/shipForm');
    Route::post('add-ship',[ShipController::class,'addShip'])->name('admin/addShip');
    Route::post('update-ship/{id}',[ShipController::class,'updateShip'])->name('admin/updateShip');
    Route::post('delete-ship/{id}',[ShipController::class,'deleteShip'])->name('admin/deleteShip');
    /* Cruise End */

    /* Destinations Start */
    Route::get('destinations',[DestinationController::class,'destinations'])->name('admin/destinations');
    Route::any('destination-form/{id?}',[DestinationController::class,'destinationForm'])->name('admin/destinationForm');
    Route::post('add-destination',[DestinationController::class,'addDestination'])->name('admin/addDestination');
    Route::post('update-destination/{id}',[DestinationController::class,'updateDestination'])->name('admin/updateDestination');
    Route::post('delete-destination/{id}',[DestinationController::class,'deleteDestination'])->name('admin/deleteDestination');
    /* Destinations End */

    /* Activities Start */
    Route::get('activities',[ActivityController::class,'activities'])->name('admin/activities');
    Route::any('activity-form/{id?}',[ActivityController::class,'activityForm'])->name('admin/activityForm');
    Route::post('add-activity',[ActivityController::class,'addActivity'])->name('admin/addActivity');
    Route::post('update-activity/{id}',[ActivityController::class,'updateActivity'])->name('admin/updateActivity');
    Route::post('delete-activity/{id}',[ActivityController::class,'deleteActivity'])->name('admin/deleteActivity');
    /* Activities End */

    /* Adventures Start */
    Route::get('adventures',[AdventureController::class,'adventures'])->name('admin/adventures');
    Route::any('adventure-form/{id?}',[AdventureController::class,'adventureForm'])->name('admin/adventureForm');
    Route::post('add-adventure',[AdventureController::class,'addAdventure'])->name('admin/addAdventure');
    Route::post('update-adventure/{id}',[AdventureController::class,'updateAdventure'])->name('admin/updateAdventure');
    Route::post('delete-adventure/{id}',[AdventureController::class,'deleteJourney'])->name('admin/deleteJourney');
    /* Adventures End */

    /* Reviews & Testimonials Start */
    Route::get('reviews',[ReviewController::class,'reviews'])->name('admin/reviews');
    Route::any('review-details/{id}',[ReviewController::class,'reviewDetails'])->name('admin/reviewDetails');
    Route::get('testimonials',[ReviewController::class,'testimonials'])->name('admin/testimonials');
    Route::any('testimonial-details/{id}',[ReviewController::class,'testimonialDetails'])->name('admin/testimonialDetails');
    /* Reviews & Testimonials End */

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

    // For Toggle Switch
    Route::post('toggleStatus',[AdminController::class,'toggleStatus'])->name('toggleStatus');

    Route::fallback(function () {
        $data['admin_detail'] = admin_detail();
        return response()->view('errors.admin-404', $data, 404);
    });

});
