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
Route::get('logout', "LoginController@logout");
Route::get('reset/{token}', 'ForgotPasswordController@reset')->name('resetPassword');
Route::get('forgot-password', 'ForgotPasswordController@forgotPassword')->name('forgotPassword');
Route::post('forgot-password', 'ForgotPasswordController@storeForgotPassword')->name('storeForgotPassword');
Route::post('change-password', 'ForgotPasswordController@changePassword')->name('changePassword');

Route::group([
    'middleware' => ['customer']
], function () {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('admin', AdminController::class);

    //teacher
    Route::resource('teacher', TeacherController::class);
    Route::get('teacher/{id}/lesson', 'TeacherController@lesson')->name('teacher.lesson');
    Route::post('teacher/{id}/lesson', 'TeacherController@registerLesson')->name('teacher.registerLesson');
    Route::delete('teacher/{id}/lesson/{lessonId}/delete', 'TeacherController@teacherLessonDelete')->name('teacher.teacherLessonDelete');
    Route::post('changeStatusAdmin/{id}', 'AdminController@changeStatus')->name('changeStatusAdmin');
    Route::resource('faq', FaqController::class);

    //text
    Route::resource('text', TextController::class);


    Route::resource('news', NewsController::class);
    Route::post('changeStatusNews/{id}', 'NewsController@changeStatus')->name('changeStatusNews');
    Route::get('/news/{id}/edit-lang/{type}', 'NewsController@editLang')->name('editLangNews');
    Route::post('updateLangNews', 'NewsController@updateLang')->name('updateLangNews');
    Route::get('/faq/{id}/edit-lang/{type}', 'FaqController@editLang')->name('editLangFaq');
    Route::post('updateLangFaq', 'FaqController@updateLang')->name('updateLangFaq');
    Route::resource('inquiry', InquiryController::class);
    Route::post('changeInquiryFlag', 'InquiryController@changeInquiryFlag')->name('changeInquiryFlag');
    Route::get('exportInquiry/{searchInput?}', 'InquiryController@exportInquiry')->name('exportInquiry');

    // lesson
    Route::resource('lesson', LessonController::class);
    Route::delete('lesson/{id}/text/{textId}/delete', 'LessonController@textLessonDelete')->name('lesson.textLessonDelete');
    Route::post('lesson/{id}/textLesson', 'LessonController@registerTextLesson')->name('lesson.registerTextLesson');
    Route::get('lesson/{id}/textLesson', 'LessonController@textLesson')->name('lesson.textLesson');

    //course course set
    Route::resource('course', courseController::class);
    Route::get('course/set/create', 'CourseController@courseSetCreate')->name('course.setCreate');
    Route::post('course/set/store', 'CourseController@courseSetStore')->name('course.setStore');
    Route::get('course/set/get-course/{id}', 'CourseController@getCourse')->name('course.getCourse');
    Route::delete('course/{id}/lesson/{lessonId}/delete', 'CourseController@lessonDelete')->name('course.lessonDelete');
    Route::post('course/{id}/lesson', 'CourseController@registerLesson')->name('course.registerLesson');
    Route::get('course/{id}/lesson', 'CourseController@lesson')->name('course.lesson');
    Route::get('course/set/{id}', 'CourseController@setShow')->name('course.setShow');
    Route::post('course/{id}/registerVideo', 'CourseController@registerVideo')->name('course.registerVideo');
    Route::delete('course/{id}/video/{videoId}/delete', 'CourseController@videoDelete')->name('course.videoDelete');
    Route::get('course/set/{id}/edit', 'CourseController@setEdit')->name('course.setEdit');
    Route::post('course/set/{id}/update', 'CourseController@setUpdate')->name('course.setUpdate');

   //lesson cancel history
    Route::resource('lessonCancelHistory', LessonCancelHistoryController::class);

    //remind mail
    Route::resource('remindmail', RemindMailController::class);

    //Category
    Route::resource('category', CategoryController::class);
    Route::delete('category/{id}/course/{courseId}/delete', 'CategoryController@courseDelete')->name('category.courseDelete');
    Route::post('category/{id}/course', 'CategoryController@registerCourse')->name('category.registerCourse');
    Route::get('category/{id}/course', 'CategoryController@course')->name('category.course');

    //csvExport
    Route::resource('csv', CsvController::class);
    Route::post('/csv/exportPayment', 'CsvController@exportPayment')->name('csvExportPayment');
    Route::post('/csv/exportLessonHistory', 'CsvController@exportLessonHistory')->name('csvExportLessonHistory');
    Route::post('/csv/exportSuperGrace', 'CsvController@exportSuperGrace')->name('exportSuperGrace');
    Route::post('/csv/exportLessonSummaryProcess', 'CsvController@exportLessonSummaryProcess')->name('exportLessonSummaryProcess');
    Route::post('/csv/exportStudentBoughtCourse', 'CsvController@exportStudentBoughtCourse')->name('exportStudentBoughtCourse');
    Route::post('/csv/exportSuperGraceNormal', 'CsvController@exportSuperGraceNormal')->name('exportSuperGraceNormal');

    //inquirySubject
    Route::resource('inquirySubject', InquirySubjectController::class);
    Route::get('/inquirySubject/{id}/edit-lang/{type}', 'InquirySubjectController@editLang')->name('editLangInquirySubject');
    Route::post('updateLangInquirySubject', 'InquirySubjectController@updateLang')->name('updateLangInquirySubject');

    //comment list
    Route::get('/comment', 'CommentController@index')->name('comment.index');

    //student
    Route::resource('student', StudentController::class);
    Route::get('/student/comment/{id}', 'StudentController@comment')->name('student.commentList');
    Route::delete('/student/destroy-comment/{id}', 'StudentController@destroyComment')->name('student.destroyComment');
    Route::get('/student/create-comment/{id}', 'StudentController@createComment')->name('student.createComment');
    Route::post('/student/create-comment', 'StudentController@storeComment')->name('student.storeComment');
    Route::get('/student/edit-comment/{id}', 'StudentController@editComment')->name('student.editComment');
    Route::post('/student/update-comment', 'StudentController@updateComment')->name('student.updateComment');

    Route::get('/student/lesson-history/{id}', 'StudentController@lessonHistory')->name('student.lessonHistoryList');
    Route::get('/student/show-lesson-history/{id}', 'StudentController@showLessonHistory')->name('student.showLessonHistory');
    Route::post('/student/update-lesson-history', 'StudentController@updateLessonsHistory')->name('student.updateLessonsHistory');
    Route::post('/student/cancel-lesson-history', 'StudentController@cancelLessonsHistory')->name('student.cancelLessonsHistory');
});
