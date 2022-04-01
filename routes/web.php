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
    Route::get('export-teacher', 'TeacherController@exportTeacher')->name('exportTeacher');


    //-------------------------- 学習管理--------------------------------//
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
    Route::get('lesson/preparation', 'LessonController@preparation')->name('lesson.preparation');
    Route::post('lesson/preparation', 'LessonController@registerPreparation')->name('lesson.registerPreparation');
    Route::delete('lesson/{id}/preparation/{preparationId}/delete', 'LessonController@preparationDelete')->name('lesson.preparationDelete');
    Route::get('lesson/review', 'LessonController@review')->name('lesson.review');
    Route::post('lesson/review', 'LessonController@registerReview')->name('lesson.registerReview');
    Route::delete('lesson/{id}/review/{reviewId}/delete', 'LessonController@reviewDelete')->name('lesson.reviewDelete');
    Route::resource('lesson', LessonController::class);
    Route::delete('lesson/{id}/text/{textId}/delete', 'LessonController@textLessonDelete')->name('lesson.textLessonDelete');
    Route::post('lesson/{id}/textLesson', 'LessonController@registerTextLesson')->name('lesson.registerTextLesson');
    Route::get('lesson/{id}/textLesson', 'LessonController@textLesson')->name('lesson.textLesson');
    Route::get('/lesson/{id}/edit-lang/{type}', 'LessonController@editLang')->name('lesson.editLang');
    Route::post('update_lang_lesson', 'LessonController@updateLang')->name('lesson.updateLang');
    Route::get('lesson/{id}/confirmTest', 'LessonController@confirmTest')->name('lesson.confirmTest');
    Route::post('lesson/{id}/confirmTest', 'LessonController@registerConfirmTest')->name('lesson.registerConfirmTest');
    Route::delete('lesson/{id}/confirmTest/{testId}/delete', 'LessonController@confirmTestDelete')->name('lesson.confirmTestDelete');

    //course course set
    Route::resource('course', CourseController::class);
    Route::get('course/{id}/test/ability', 'CourseController@testAbility')->name('course.test.ability');
    Route::delete('course/{id}/test/{testId}/delete', 'CourseController@testDelete')->name('course.testDelete');
    Route::post('course/{id}/test/ability', 'CourseController@testAbilityUpdate')->name('course.test.abilityUpdate');
    Route::get('course/{id}/test/course_end', 'CourseController@testCourseEnd')->name('course.test.courseEnd');
    Route::post('course/{id}/test/course_end', 'CourseController@testCourseEndUpdate')->name('course.test.courseEndUpdate');
    Route::get('course/{id}/lesson', 'CourseController@lesson')->name('course.lesson');
    Route::get('/course/{id}/edit-lang/{type}', 'CourseController@editLang')->name('course.editLang');
    Route::get('course/{id}/lesson_attach', 'CourseController@lessonAttach')->name('course.lessonAttach');
    Route::post('course/{id}/lesson_attach', 'CourseController@lessonAttachUpdate')->name('course.lessonAttachUpdate');
    Route::post('update_lang_course', 'CourseController@updateLang')->name('course.updateLang');
    //campaign
    Route::get('/course/{courseId}/add-campaign', 'CourseController@campaignCreate')->name('course.campaignCreate');
    Route::post('/course/{courseId}/add-campaign-store', 'CourseController@addCampaignStore')->name('course.addCampaignStore');
    Route::post('/course/{courseId}/exists_campaign_datetime', 'CourseController@existCampaignDatetime')->name('course.existCampaignDatetime');
    Route::delete('/course/{courseId}/campaign/destroy/{campaignId}', 'CourseController@campaignDestroy')->name('course.campaign.destroy');

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

    //TEST
    Route::get('test/demo', 'TestController@demo')->name('test.demo');
    Route::resource('test', TestController::class);
    Route::get('test/{id}/add_question', 'TestController@addQuestion')->name('test.addQuestion');
    Route::post('test/{id}/add_question', 'TestController@addQuestionPost')->name('test.addQuestionPost');
    Route::get('test/{id}/edit_question/{testQuestionId}', 'TestController@editQuestion')->name('test.editQuestion');
    Route::post('test/{id}/edit_question/{testQuestionId}', 'TestController@QuestionUpdate')->name('test.editQuestionUpdate');
    Route::delete('test/{id}/delete_question/{testQuestionId}', 'TestController@deleteQuestion')->name('test.deleteQuestion');
    Route::get('test/{id}/list_question_attach', 'TestController@listQuestionAttach')->name('test.listQuestionAttach');
    Route::post('test/{id}/list_question_attach', 'TestController@listQuestionAttachUpdate')->name('test.listQuestionAttachUpdate');
    Route::post('test/{id}/check_navigation', 'TestController@checkNavigation')->name('test.checkNavigation');
    //tag
    Route::post('test/{id}/add_tag', 'TestController@addTag')->name('test.createTag');




    //-------------------------- 学習管理--------------------------------//


    //lesson cancel history
    Route::resource('lessonCancelHistory', LessonCancelHistoryController::class);

    //remind mail
    Route::resource('remindmail', RemindMailController::class);

    //Category
    Route::resource('category', CategoryController::class);
    Route::delete('category/{id}/course/{courseId}/delete', 'CategoryController@courseDelete')->name('category.courseDelete');
    Route::post('category/{id}/course', 'CategoryController@registerCourse')->name('category.registerCourse');
    Route::get('category/{id}/course', 'CategoryController@course')->name('category.course');
    Route::get('/category/{id}/edit-lang/{type}', 'CategoryController@editLang')->name('category.editLang');
    Route::post('updateLangCategory', 'CategoryController@updateLang')->name('category.updateLang');

    //preparation
    Route::resource('preparation', PreparationController::class);

    //review
    Route::resource('review', ReviewController::class);

    //file
    Route::get('files/get_files', 'FileController@getFiles')->name('files.getFiles');

    //inquirySubject
    Route::resource('inquirySubject', InquirySubjectController::class);
    Route::get('/inquirySubject/{id}/edit-lang/{type}', 'InquirySubjectController@editLang')->name('editLangInquirySubject');
    Route::post('updateLangInquirySubject', 'InquirySubjectController@updateLang')->name('updateLangInquirySubject');

    //lessonStatus
    Route::resource('lessonStatus', LessonStatusController::class);
    Route::post('getDataLessonStatus', 'LessonStatusController@getData')->name('getDataLessonStatus');
    Route::post('/lessonStatus/lessonInfomationDetailExportCsv', 'LessonStatusController@lessoninfomationdetailexportcsv')->name('lessonInfomationDetailExportCsv');
    Route::post('/lessonStatus/lessonInfomationStatusExportCsv', 'LessonStatusController@lessoninfomationstatusexportcsv')->name('lessonInfomationStatusExportCsv');
    Route::post('/lessonStatus/updateLessonStatus', 'LessonStatusController@updateLessonStatus')->name('updateLessonStatus');
    Route::post('/lessonStatus/copySettingLessonFree', 'LessonStatusController@copySettingLessonFree')->name('copySettingLessonFree');
    //comment list
    Route::get('/comment', 'CommentController@index')->name('comment.index');

    //student
    Route::get('/student', 'StudentController@index')->name('student.index');
    Route::get('/student/edit/{id}', 'StudentController@edit')->name('student.edit');
    Route::put('/student/update', 'StudentController@update')->name('student.update');
    Route::get('/student/export', 'StudentController@export')->name('student.export');
    Route::delete('/student/destroy/{id}', 'StudentController@destroy')->name('student.destroy');
    Route::post('/student/updatePassword', 'StudentController@updatePassword')->name('student.updatePassword');

    Route::get('/student/comment/{id}', 'StudentController@comment')->name('student.commentList');
    Route::delete('/student/destroy-comment/{id}', 'StudentController@destroyComment')->name('student.destroyComment');
    Route::get('/student/create-comment/{id}', 'StudentController@createComment')->name('student.createComment');
    Route::post('/student/create-comment', 'StudentController@storeComment')->name('student.storeComment');
    Route::get('/student/edit-comment/{id}', 'StudentController@editComment')->name('student.editComment');
    Route::post('/student/update-comment', 'StudentController@updateComment')->name('student.updateComment');

    Route::get('/student/lesson-history/{id}', 'StudentController@lessonHistory')->name('student.lessonHistoryList');
    Route::get('/student/show-lesson-history/{id}', 'StudentController@showLessonHistory')->name('student.showLessonHistory');
    Route::post('/student/update-lesson-history', 'StudentController@updateLessonHistory')->name('student.updateLessonHistory');
    Route::post('/student/cancel-lesson-history', 'StudentController@cancelLessonHistory')->name('student.cancelLessonHistory');

    Route::get('/student/payment-history/{id}', 'StudentController@paymentHistory')->name('student.paymentHistoryList');
    Route::get('/student/create-payment-history/{id}', 'StudentController@createPaymentHistory')->name('student.createPaymentHistory');
    Route::post('/student/store-payment-history', 'StudentController@storePaymentHistory')->name('student.storePaymentHistory');
    Route::get('/student/edit-payment-history/{id}', 'StudentController@editPaymentHistory')->name('student.editPaymentHistory');
    Route::post('/student/update-payment-history', 'StudentController@updatePaymentHistory')->name('student.updatePaymentHistory');
    Route::post('/student/destroy-payment-history', 'StudentController@destroyPaymentHistory')->name('student.destroyPaymentHistory');

    Route::get('/payment-history', 'PaymentHistoryController@index')->name('paymentHistory.index');
    Route::get('/payment-history/edit/{id}', 'PaymentHistoryController@edit')->name('paymentHistory.edit');
    Route::put('/payment-history/update', 'PaymentHistoryController@update')->name('paymentHistory.update');
    Route::get('/payment-history/export', 'PaymentHistoryController@export')->name('paymentHistory.export');

    Route::resource('lessonSchedule', LessonScheduleController::class);
    Route::post('lessonSchedule/getData', 'LessonScheduleController@getData')->name('getDataLessonSchedule');
    Route::post('lessonSchedule/registerMultiLesson', 'LessonScheduleController@registerMultiLesson')->name('registerMultiLesson');
    Route::post('lessonSchedule/removeMultiLesson', 'LessonScheduleController@removeMultiLesson')->name('removeMultiLesson');
    Route::post('lessonSchedule/registerLesson', 'LessonScheduleController@registerLesson')->name('registerLesson');
    Route::get('/student/point-history/{id}', 'StudentController@pointHistory')->name('student.pointHistoryList');
    Route::get('/student/show-point-history/{id}', 'StudentController@showPointHistory')->name('student.showPointHistory');
    Route::post('/student/update-point-history', 'StudentController@updatePointHistory')->name('student.updatePointHistory');
    Route::post('/student/cancel-point-history', 'StudentController@cancelPointHistory')->name('student.cancelPointHistory');

    Route::get('/admin/edit-role/{id}', 'AdminController@editRole')->name('admin.editRole');
    Route::post('/admin/update-role', 'AdminController@updateRole')->name('admin.updateRole');


    // ablity test result
    Route::resource('/abilityTestResult', AbilityTestResultController::class);
    Route::post('/abilityTestResult/update-test-comment/{testCommentId}', 'AbilityTestResultController@updateTestComment')->name('abilityTestResult.updateTestComment');
    Route::get('/abilityTestResult/{id}/edit/answerDetail', 'AbilityTestResultController@answerDetail')->name('abilityTestResult.answerDetail');

    // group lesson history
    Route::resource('/group_lesson_history', GroupLessonHistoryController::class);



    Route::get('/groupSchedule', 'GroupScheduleController@index')->name('groupSchedule.index');
    Route::get('/groupSchedule/getData', 'GroupScheduleController@getData')->name('groupSchedule.getData');
    Route::get('/groupSchedule/getSchedule', 'GroupScheduleController@getSchedule')->name('groupSchedule.getSchedule');
    Route::post('/groupSchedule/registerSchedule', 'GroupScheduleController@registerSchedule')->name('groupSchedule.registerSchedule');
    Route::get('/groupSchedule/getZoom', 'GroupScheduleController@getZoom')->name('groupSchedule.getZoom');

    //zoom account
    Route::resource('zoomAccount', ZoomAccountController::class);
    Route::get('/zoomSetting/edit', 'ZoomSettingController@edit')->name('zoomSetting.edit');
    Route::post('/zoomSetting/update', 'ZoomSettingController@update')->name('zoomSetting.update');

    //group lesson
    Route::resource('groupLessonReserves', GroupLessonReserveController::class);

    Route::get('export-group-lesson', 'GroupLessonHistoryController@exportGroupLesson')->name('exportGroupLesson');
    Route::get('/group_lesson_history/student_attendance/{id}', 'GroupLessonHistoryController@studentAttendance')->name('groupLessonHistory.studentAttendance');
    Route::post('/group_lesson_history/update-student-attendace/{id}', 'GroupLessonHistoryController@updateStudentAttendance')->name('groupLessonHistory.updateStudentAttendance');

    Route::get('/remindMail/{id}/edit-lang/{type}', 'RemindMailController@editLang')->name('editLangRemindMail');
    Route::post('updateLangRemindMail', 'RemindMailController@updateLang')->name('updateLangRemindMail');

    Route::get('/teacher/lesson-history/{id}', 'TeacherController@lessonHistory')->name('teacher.lessonHistory');
    Route::get('/teacher/lesson-history-export/{id}', 'TeacherController@lessonHistoryExport')->name('teacher.lessonHistoryExport');
    Route::get('/teacher/lesson-history-detail/{id}', 'TeacherController@lessonHistoryDetail')->name('teacher.lessonHistoryDetail');
    Route::get('/teacher/{id}/edit-lang/{type}', 'TeacherController@editLang')->name('teacher.editLang');
    Route::post('updateTeacherLang', 'TeacherController@updateLang')->name('teacher.updateLang');
    Route::post('/teacher/update-password', 'TeacherController@updatePassword')->name('teacher.updatePassword');
    Route::get('groupLessonStudentList/{id}', 'GroupLessonReserveController@getStudent')->name('groupLesson.getStudent');
});
