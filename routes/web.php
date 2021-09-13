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
Route::get('/', function () {
    return redirect('/login');
});
Route::resource('login', LoginController::class);
Route::resource('forgot_password', ForgotPasswordController::class);
Route::get('logout', "LoginController@logout");
Route::get('reset/{token}', 'ForgotPasswordController@reset')->name('reset-password');
Route::post('change_password', 'ForgotPasswordController@changePassword');

Route::group([
    'middleware' => ['customer']
], function () {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('admin', AdminController::class);
    Route::resource('teacher', TeacherController::class);
    Route::post('changeStatus/{id}', 'AdminController@changeStatus')->name('changeStatus');
});
