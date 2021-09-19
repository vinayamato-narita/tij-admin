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
    Route::post('changeStatusAdmin/{id}', 'AdminController@changeStatus')->name('changeStatusAdmin');
    Route::resource('faq', FaqController::class);
    Route::resource('text', TextController::class);
    Route::resource('news', NewsController::class);
    Route::post('changeStatusNews/{id}', 'NewsController@changeStatus')->name('changeStatusNews');
    Route::get('/news/{id}/edit-lang/{type}', 'NewsController@editLang')->name('editLangNews');
    Route::post('updateLangNews', 'NewsController@updateLang')->name('updateLangNews');
    Route::resource('lesson', LessonController::class);
    Route::get('/faq/{id}/edit-lang/{type}', 'FaqController@editLang')->name('editLangFaq');
    Route::post('updateLangFaq', 'FaqController@updateLang')->name('updateLangFaq');
    Route::resource('inquiry', InquiryController::class);
    Route::post('changeInquiryFlag', 'InquiryController@changeInquiryFlag')->name('changeInquiryFlag');
    Route::get('exportInquiry/{searchInput?}', 'InquiryController@exportInquiry')->name('exportInquiry');
});
