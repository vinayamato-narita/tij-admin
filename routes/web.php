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
    Route::resource('faq', FaqController::class);
    Route::resource('text', TextController::class);

    //csvExport
    Route::resource('csv', CsvController::class);
    Route::post('/csv/exportPayment', 'CsvController@exportPayment')->name('csvExportPayment');
    Route::post('/csv/exportLessonHistory', 'CsvController@exportLessonHistory')->name('csvExportLessonHistory');
    Route::post('/csv/exportSuperGrace', 'CsvController@exportSuperGrace')->name('exportSuperGrace');
    Route::post('/csv/exportLessonSummaryProcess', 'CsvController@exportLessonSummaryProcess')->name('exportLessonSummaryProcess');
    Route::post('/csv/exportStudentBoughtCourse', 'CsvController@exportStudentBoughtCourse')->name('exportStudentBoughtCourse');
    Route::post('/csv/exportSuperGraceNormal', 'CsvController@exportSuperGraceNormal')->name('exportSuperGraceNormal');
});
