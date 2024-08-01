<?php

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('login/google', 'Auth\LoginController@redirectToGoogle');
Route::get('login/google/callback', 'Auth\LoginController@handleGoogleCallback');
Route::get('getUserDetails/{token}', 'Auth\LoginController@getUserByToken');
Route::post('order-payment/subscription-confirmation','payment\paymentController@confirmPayment')->name('order-payment/subscription-confirmation');

Route::post('order-payment/subscription-cancelled','payment\paymentController@cancelPayment')->name('order-payment/subscription-cancelled');

Route::resource('appDepartments', 'app_departmentController');

Route::resource('appSubjects', 'app_subjectController');

// Route::post('/save-teacher-detail','TeacherDetails@save_teacher_detail');