<?php

namespace App\Console\Commands;

use App\Enums\CourseTypeEnum;
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
        $courses = Course::whereDate('decide_date', $yesterday)->where('course_type', CourseTypeEnum::GROUP_COURSE)->with(['pointSubscriptionHistories', 'pointSubscriptionHistories.student', 'lessonSchedules', 'lessonSchedules.teacher'])->get();
       
        foreach ($courses as $course) {
            $reserveNum = $course->pointSubscriptionHistories->count();

            if ($reserveNum < $course->min_reserve_count) {
               
                //send mail student
                foreach ($course->pointSubscriptionHistories as $pointSubscriptionHistory) {

                    $langType = 'jp';

                    if ($pointSubscriptionHistory->student->lang_type == 2) {
                        $langType = 'en';
                    } else if ($pointSubscriptionHistory->student->lang_type == 3) {
                        $langType = 'vn';
                    }

                    $mailPattern = SendRemindMailPattern::getRemindmailPatternInfo(MailType::STUDENT_CANCEL_LESSON, $langType);

                    if ($mailPattern) {
                        $mailSubject = $mailPattern->mail_subject;
                        $mailBody = $mailPattern->mail_body;
                        $mailBody = str_replace("#STUDENT_NAME#", $pointSubscriptionHistory->student->student_name, $mailBody);

                        Mail::raw($mailBody, function ($message) use ($pointSubscriptionHistory, $mailSubject) {
                            $message->to($pointSubscriptionHistory->student->student_email)
                                ->subject($mailSubject);
                        });
                    }
                }
                //send mail teacher
                foreach ($course->lessonSchedules as $lessonSchedule) {
                    $mailPattern = SendRemindMailPattern::getRemindmailPatternInfo(MailType::TEACHER_CANCEL_LESSON, $langType);

                    if ($mailPattern) {
                        $mailSubject = $mailPattern->mail_subject;
                        $mailBody = $mailPattern->mail_body;
                        $mailBody = str_replace("#TEACHER_NAME#", $lessonSchedule->teacher->teacher_name, $mailBody);

                        Mail::raw($mailBody, function ($message) use ($lessonSchedule, $mailSubject) {
                            $message->to($lessonSchedule->teacher->teacher_email)
                                ->subject($mailSubject);
                        });
                    }
                }
                //delete data
                PointSubscriptionHistory::where('course_id', $course->course_id)->delete();
                StudentPointHistory::where('course_id', $course->course_id)->delete();
                LessonSchedule::where('course_id', $course->course_id)->delete();
                LessonHistory::where('course_id', $course->course_id)->delete();
            }
        }

        Log::info('group course decision batch end');
    }
}
