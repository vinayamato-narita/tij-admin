<?php

namespace App\Console\Commands;

use App\Enums\CourseTypeEnum;
use App\Enums\GroupLessonStatus;
use App\Enums\MailType;
use App\Models\Course;
use App\Models\LessonHistory;
use App\Models\LessonSchedule;
use App\Models\PointSubscriptionHistory;
use App\Models\SendRemindMailPattern;
use App\Models\StudentPointHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GroupCourseDecision extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group_course_decision';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '開催決定時間になったグループレッスンのコースを開催するかどうか確認し';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('group course decision batch start');

        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $courses = Course::whereDate('decide_date', $yesterday)->where('course_type', CourseTypeEnum::GROUP_COURSE)->where('group_lesson_status', GroupLessonStatus::BEFORE_DECIDE)->with(['pointSubscriptionHistories', 'pointSubscriptionHistories.student', 'lessonSchedules', 'lessonSchedules.teacher', 'lessonSchedules.teacher.teacherInfo'])->get();
        $cancelCourseIds = [];
        $decideCourseIds = [];

        foreach ($courses as $course) {
            try {
                $reserveNum = $course->pointSubscriptionHistories->count();

                if ($reserveNum < $course->min_reserve_count) {
                    $cancelCourseIds[] = $course->course_id;

                    //send mail student
                    foreach ($course->pointSubscriptionHistories as $pointSubscriptionHistory) {

                        $studentName = $pointSubscriptionHistory->student->student_name;
                        $lessonDate = '';
                        $lessonTime = '';
                        $lessonName = '';
                        $lessonTextName = '';
                        $teacherNickname = '';

                        foreach ($pointSubscriptionHistory->studentPointHistories as $studentPointHistory) {
                            if ($studentPointHistory->student_id == $pointSubscriptionHistory->student_id && $studentPointHistory->course_id == $pointSubscriptionHistory->course_id) {
                                $lessonDate = Carbon::parse($studentPointHistory->lessonSchedule->lesson_date)->format('Y年m月d日');
                                $lessonTime = Carbon::parse($studentPointHistory->lessonSchedule->lesson_starttime)->format('H:i');
                                $lessonName = $studentPointHistory->lessonSchedule->lesson->lesson_name;
                                $lessonTextName = $studentPointHistory->lessonSchedule->lesson_text_name;
                                $teacherNickname = $studentPointHistory->lessonSchedule->teacher->teacher_nickname;
                            }
                        }

                        $langType = $pointSubscriptionHistory->student->lang_type;

                        $mailPattern = SendRemindMailPattern::getRemindmailPatternInfo(MailType::STUDENT_CANCEL_LESSON, $langType);

                        if ($mailPattern) {
                            $mailSubject = $mailPattern[0]->mail_subject;
                            $mailBody = $mailPattern[0]->mail_body;
                            $mailBody = str_replace("#STUDENT_NAME#", $studentName, $mailBody);
                            $mailBody = str_replace("#LESSON_DATE#", $lessonDate, $mailBody);
                            $mailBody = str_replace("#LESSON_TIME#", $lessonTime, $mailBody);
                            $mailBody = str_replace("#LESSON_NAME#", $lessonName, $mailBody);
                            $mailBody = str_replace("#LESSON_TEXT_NAME#", $lessonTextName, $mailBody);
                            $mailBody = str_replace("#TEACHER_NICKNAME#", $teacherNickname, $mailBody);

                            Mail::raw($mailBody, function ($message) use ($pointSubscriptionHistory, $mailSubject) {
                                $message->to($pointSubscriptionHistory->student->student_email)
                                    ->subject($mailSubject);
                            });
                        }
                    }

                    //send mail teacher
                    foreach ($course->lessonSchedules as $lessonSchedule) {

                        $langTypeTeacher  = $lessonSchedule->teacher->teacherInfo->lang_type;

                        $mailPattern = SendRemindMailPattern::getRemindmailPatternInfo(MailType::TEACHER_CANCEL_LESSON, $langTypeTeacher);

                        if ($mailPattern) {
                            $mailSubject = $mailPattern[0]->mail_subject;
                            $mailBody = $mailPattern[0]->mail_body;
                            $mailBody = str_replace("#LESSON_DATE#", Carbon::parse($lessonSchedule->lesson_date)->format('Y年m月d日'), $mailBody);
                            $mailBody = str_replace("#LESSON_TIME#", Carbon::parse($lessonSchedule->lesson_starttime)->format('H:i'), $mailBody);
                            $mailBody = str_replace("#LESSON_NAME#", $lessonSchedule->lesson->lesson_name, $mailBody);
                            $mailBody = str_replace("#LESSON_TEXT_NAME#", $lessonSchedule->lesson_text_name, $mailBody);
                            $mailBody = str_replace("#TEACHER_NICKNAME#", $lessonSchedule->teacher->teacher_nickname, $mailBody);

                            Mail::raw($mailBody, function ($message) use ($lessonSchedule, $mailSubject) {
                                $message->to($lessonSchedule->teacher->teacher_email)
                                    ->subject($mailSubject);
                            });
                        }
                    }
                } else {
                    $decideCourseIds[] = $course->course_id;
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        }

        if (!empty($cancelCourseIds)) {
            Course::whereIn('course_id', $cancelCourseIds)->update(['group_lesson_status' => GroupLessonStatus::CANCEL]);
            PointSubscriptionHistory::whereIn('course_id', $cancelCourseIds)->delete();
            StudentPointHistory::whereIn('course_id', $cancelCourseIds)->delete();
            LessonSchedule::whereIn('course_id', $cancelCourseIds)->delete();
            LessonHistory::whereIn('course_id', $cancelCourseIds)->delete();
        }

        if (!empty($decideCourseIds)) {
            Course::whereIn('course_id', $decideCourseIds)->update(['group_lesson_status' => GroupLessonStatus::COURSE_DECIDE]);
        }

        Log::info('group course decision batch end');
    }
}
