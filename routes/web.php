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
Route::get('admin/logout',[AdminController::class,'logout'])->name('admin/logout');

Route::get('admin/forgot-password',[AdminController::class,'forgot_password'])->name('admin/forgot_password');
Route::post('do-forgot-password',[AdminController::class,'do_forgot_password'])->name('admin/do_forgot_password');
Route::get('admin/reset-password/{admin_id}/{unique_id}',[AdminController::class,'reset_password'])->name('admin/reset_password');
Route::post('admin/do-password-reset',[AdminController::class,'do_reset_password'])->name('admin/do_reset_password');

Route::group(['prefix' => 'admin', 'middleware' => AdminAuth::class], function () {
    Route::get('dashboard',[AdminController::class,'dashboard'])->name('admin/dashboard');

    Route::get('profile',[AdminController::class,'profile'])->name('admin/profile');
    Route::post('update-profile',[AdminController::class,'updateProfile'])->name('admin/updateProfile');

    Route::get('users',[UserController::class,'users'])->name('admin/users');
    Route::get('user-details/{user_id}',[UserController::class,'userDetails'])->name('admin/userDetails');
    
    Route::get('surveys',[SurveyController::class,'surveys'])->name('admin/surveys');
    Route::get('survey-details/{survey_id}',[SurveyController::class,'surveyDetails'])->name('admin/surveyDetails');

    Route::get('social',[AdminController::class,'social'])->name('admin/social');
    Route::post('update-social-link',[AdminController::class,'update_social_link'])->name('admin/update-social-link');

    Route::post('changePassword',[AdminController::class,'changePassword'])->name('admin/changePassword');

    Route::get('site-setting/{key}',[AdminController::class,'siteSetting'])->name('admin/siteSetting');
    Route::get('site-setting/{key}',[AdminController::class,'siteSetting'])->name('admin/siteSetting');
    Route::get('site-setting/{key}',[AdminController::class,'siteSetting'])->name('admin/siteSetting');
    Route::post('update-site-setting',[AdminController::class,'updateSiteSetting'])->name('admin/updateSiteSetting');

    Route::post('faq',[AdminController::class,'faq'])->name('admin/faq');
    Route::post('faqform/{key?}',[AdminController::class,'faqform'])->name('admin/faqform');
    Route::post('faqform/{key?}',[AdminController::class,'faqform'])->name('admin/faqform');
    Route::post('faqform/{key?}',[AdminController::class,'faqform'])->name('admin/faqform');
    Route::post('add_faq',[AdminController::class,'add_faq'])->name('admin/add_faq');
    Route::post('update_faq',[AdminController::class,'update_faq'])->name('admin/update_faq');
    Route::get('delete_faq/{key}',[AdminController::class,'delete_faq'])->name('admin/delete_faq');
    Route::post('change_status/{id}/{status}/{table}/{wherecol}/{statusvariable}',[AdminController::class,'change_status'])->name('admin/change_status');

    //-----------------------------notification--------------------------------
    Route::post('notification',[AdminController::class,'notifiction'])->name('admin/notification');
     Route::post('sendNotficationToAll',[AdminController::class,'sendNotficationToAll'])->name('admin/sendNotficationToAll');

    /*Contact Start*/
    Route::get('contactus',[AdminController::class,'contactus'])->name('admin/contactus');
    Route::get('add-contact',[AdminController::class,'open_contact_form'])->name('admin/add-contact');
    Route::get('update-contact',[AdminController::class,'open_contact_form'])->name('admin/update-contact');
    Route::post('do-update-contact',[AdminController::class,'doUpdateContact'])->name('admin/do-update-contact');
    /*Contact End*/

    Route::fallback(function () {
        return response()->view('errors.404', [], 404);
    });

});
