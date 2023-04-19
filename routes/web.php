<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuth;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\SurveyController;

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

Route::get('admin/forget-password',[AdminController::class,'forget_password'])->name('admin/forget_password');
Route::get('admin/forgot-password',[AdminController::class,'forgot_password'])->name('admin/forgot_password');
Route::post('do-forgot-password',[AdminController::class,'do_forgot_password'])->name('admin/do_forgot_password');
Route::get('admin/reset-password/{admin_id}/{unique_id}',[AdminController::class,'reset_password'])->name('admin/reset_password');
Route::post('admin/do-password-reset',[AdminController::class,'do_reset_password'])->name('admin/do_reset_password');

Route::group(['prefix' => 'admin', 'middleware' => AdminAuth::class], function () {
    Route::get('dashboard',[AdminController::class,'dashboard'])->name('admin/dashboard');

    Route::get('profile',[AdminController::class,'profile'])->name('admin/profile');
    Route::post('update-profile',[AdminController::class,'updateProfile'])->name('admin/updateProfile');
    
    Route::get('change-password',[AdminController::class,'changePassword'])->name('admin/changePassword');
    Route::post('update-password',[AdminController::class,'updatePassword'])->name('admin/updatePassword');

    Route::get('users',[UserController::class,'users'])->name('admin/users');
    Route::get('user-details/{user_id}',[UserController::class,'userDetails'])->name('admin/userDetails');
    Route::post('change-user-status/{id}/{status}/{table}/{wherecol}/{statusvariable}',[AdminController::class,'change_user_status'])->name('admin/change_user_status');
    
    Route::get('surveys',[SurveyController::class,'surveys'])->name('admin/surveys');
    Route::get('survey-details/{survey_id}',[SurveyController::class,'surveyDetails'])->name('admin/surveyDetails');

    Route::get('social',[AdminController::class,'social'])->name('admin/social');
    Route::post('update-social-link',[AdminController::class,'update_social_link'])->name('admin/update-social-link');

    Route::get('site-setting/{key}',[AdminController::class,'siteSetting'])->name('admin/siteSetting');
    Route::get('site-setting/{key}',[AdminController::class,'siteSetting'])->name('admin/siteSetting');
    Route::get('site-setting/{key}',[AdminController::class,'siteSetting'])->name('admin/siteSetting');
    Route::post('update-site-setting',[AdminController::class,'updateSiteSetting'])->name('admin/updateSiteSetting');

    Route::get('faqs',[AdminController::class,'faqs'])->name('admin/faqs');
    Route::any('open-faq-form',[AdminController::class,'openFaqForm'])->name('admin/openFaqForm');
    Route::post('add-faq',[AdminController::class,'addFaq'])->name('admin/addFaq');
    Route::post('update-faq/{faq_id}',[AdminController::class,'updateFaq'])->name('admin/updateFaq');
    Route::post('delete-faq/{faq_id}',[AdminController::class,'deleteFaq'])->name('admin/deleteFaq');
    Route::post('change-faq-status/{id}/{status}/{table}/{wherecol}/{statusvariable}',[AdminController::class,'change_faq_status'])->name('admin/change_faq_status');

    //-----------------------------notification--------------------------------
    Route::post('notification',[AdminController::class,'notification'])->name('admin/notification');
    Route::post('send-notification',[AdminController::class,'sendNotification'])->name('admin/sendNotification');

    /*Contact Start*/
    Route::get('contact-details',[AdminController::class,'contactDetails'])->name('admin/contactDetails');
    Route::post('open-contact-form',[AdminController::class,'openContactForm'])->name('admin/openContactForm');
    Route::post('update-contact-details/{contact_detail_id}',[AdminController::class,'updateContactDetails'])->name('admin/updateContactDetails');
    /*Contact End*/

    Route::fallback(function () {
        return response()->view('errors.404', [], 404);
    });

});
