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
    Route::get('teacher/{id}/lesson', 'TeacherController@lesson')->name('teacher.lesson');
    Route::post('teacher/{id}/lesson', 'TeacherController@registerLesson')->name('teacher.registerLesson');
    Route::delete('teacher/{id}/lesson/{lessonId}/delete', 'TeacherController@teacherLessonDelete')->name('teacher.teacherLessonDelete');
    Route::post('changeStatusAdmin/{id}', 'AdminController@changeStatus')->name('changeStatusAdmin');
    Route::resource('faq', FaqController::class);
    Route::resource('text', TextController::class);
    Route::resource('news', NewsController::class);
    Route::post('changeStatusNews/{id}', 'NewsController@changeStatus')->name('changeStatusNews');
    Route::get('/news/{id}/edit-lang/{type}', 'NewsController@editLang')->name('editLangNews');
    Route::post('updateLangNews', 'NewsController@updateLang')->name('updateLangNews');
    Route::resource('lesson', LessonController::class);
    Route::delete('lesson/{id}/text/{textId}/delete', 'LessonController@textLessonDelete')->name('lesson.textLessonDelete');
    Route::post('lesson/{id}/textLesson', 'LessonController@registerTextLesson')->name('lesson.registerTextLesson');
    Route::get('lesson/{id}/textLesson', 'LessonController@textLesson')->name('lesson.textLesson');



});
