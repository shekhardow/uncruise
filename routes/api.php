<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('registration',[ApiController::class,'registration']);
Route::post('login',[ApiController::class,'login']);
Route::post('verifyLogin',[ApiController::class,'verifyLogin']);
Route::post('resendOTP',[ApiController::class,'resendOTP']);

Route::post('getProfile',[ApiController::class,'getProfile']);
Route::post('updateProfile',[ApiController::class,'updateProfile']);
Route::get('getAllCountries',[ApiController::class,'getAllCountries']);

Route::post('getSettings',[ApiController::class,'getSettings']);

Route::get('getAllShips',[ApiController::class,'getAllShips']);
Route::post('getShipDetails',[ApiController::class,'getShipDetails']);

Route::get('getAllDestinations',[ApiController::class,'getAllDestinations']);
Route::post('getDestinationDetails',[ApiController::class,'getDestinationDetails']);

Route::get('getAllAdventures',[ApiController::class,'getAllAdventures']);
Route::post('getAdventureDetails',[ApiController::class,'getAdventureDetails']);

Route::post('getAllPost',[ApiController::class,'getAllPost']);
Route::post('uploadPost',[ApiController::class,'uploadPost']);

Route::get('searchShipByKeyword',[ApiController::class,'searchShipByKeyword']);

Route::post('getUserReviews',[ApiController::class,'getUserReviews']);
Route::post('rateAdventure',[ApiController::class,'rateAdventure']);

Route::post('uploadUserDocuments',[ApiController::class,'uploadUserDocuments']);
Route::post('deleteUserDocuments',[ApiController::class,'deleteUserDocuments']);
Route::post('getUserDocuments',[ApiController::class,'getUserDocuments']);
Route::post('editUserDocumentName',[ApiController::class,'editUserDocumentName']);

Route::get('getAllTestimonials',[ApiController::class,'getAllTestimonials']);
