<?php

use Illuminate\Support\Facades\Route;

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


//routes for Installation of the Panel
Route::get("/install/setup", "SetupInstallation\SetupController@InstallStepOne");
Route::post("/install/setup/post", "SetupInstallation\SetupController@InstallPostOne");
Route::get("/install/setup/2", "SetupInstallation\SetupController@InstallStepTwo");
Route::get("/install/setup/3", "SetupInstallation\SetupController@InstallStepThree");
Route::post("/install/setup/3/post", "SetupInstallation\SetupController@InstallPostThree");
Route::get("/install/setup/4", "SetupInstallation\SetupController@InstallStepFour");
Route::post("/install/setup/4/post", "SetupInstallation\SetupController@InstallPostFour");

//payment
Route::get("/payment/make/{method}/id/{id}/price/{price}", "PaymentControllers\paymentController@paymentMake"); //run payment
Route::get("/payment/done/{payID}/{id}", "PaymentControllers\paymentController@paymentDone"); //if payment done, redirect here
//Route::get("/payment/status/{id}", "PaymentControllers\paymentController@paymentStatus"); //get payment status

Route::post("/payment/api/status/{method}/post", "PaymentControllers\paymentAPIController@sendStatus"); //api handshake post with mollie/paypal

//testing
Route::get("/testing", "BackendController@testing");

Route::get("/user/flow/{url}", "BackendController@pluginRouting")->where('url', '([$:ÄäÖöÜüßA-Za-z0-9\-\/]+)');
Route::post("/user/send/{url}", "BackendController@pluginRouting")->where('url', '([$:ÄäÖöÜüßA-Za-z0-9\-\/]+)');

//sprachrythmus
Route::get("/backend/language/{lang}", "BackendController@language");

Route::get('/', function () {
    return response("welcome");
});

Auth::routes();


