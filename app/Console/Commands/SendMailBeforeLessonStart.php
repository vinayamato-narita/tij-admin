<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LessonSchedule;
use App\Models\SendRemindMailPattern;
use App\Enums\MailType;
use App\Enums\LangType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Log;

class SendMailBeforeLessonStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_mail_before_lesson_start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send mail to student and teacher before lesson start';

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
        $getMailTeacher = SendRemindMailPattern::where('send_remind_mail_timing_type', MailType::MAIL_TEACHER_BEFORE_LESSON_START)->first();
        if($getMailTeacher == null) {
            return;
        }
        $getMailStudentJp = SendRemindMailPattern::getRemindmailPatternInfo(MailType::MAIL_STUDENT_BEFORE_LESSON_START, LangType::JP);
        $getMailStudentEn = SendRemindMailPattern::getRemindmailPatternInfo(MailType::MAIL_STUDENT_BEFORE_LESSON_START, LangType::EN);
        $getMailStudentCn = SendRemindMailPattern::getRemindmailPatternInfo(MailType::MAIL_STUDENT_BEFORE_LESSON_START, LangType::ZH);

        $timingMinutes = $getMailTeacher->timing_minutes;
        $timeStart = Carbon::now()->addMinutes($timingMinutes - 5);;
        $timeEnd = Carbon::now()->addMinutes($timingMinutes);

        $lessonSchedules = LessonSchedule::join('student_point_history', function($join) {
            $join->on('lesson_schedule.lesson_schedule_id', '=', 'student_point_history.lesson_schedule_id');
        })
        ->join('teacher', function($join) {
            $join->on('lesson_schedule.teacher_id', '=', 'teacher.teacher_id');
        })
        ->join('student', function($join) {
            $join->on('student_point_history.student_id', '=', 'student.student_id');
        })
        ->join('lesson', function($join) {
            $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
        })
        ->where('lesson_schedule.course_id', '>', 1)
        ->where('lesson_schedule.lesson_starttime', '>=', $timeStart)
        ->where('lesson_schedule.lesson_starttime', '<=', $timeEnd)
        ->get();

        foreach($lessonSchedules as $lessonSchedule) {
            if ($getMailTeacher) {
                $mailSubject = $getMailTeacher->mail_subject;
                $mailBody = $getMailTeacher->mail_body;
                $mailBody = str_replace("#LESSON_DATE#", Carbon::parse($lessonSchedule->lesson_date)->format('Y年m月d日'), $mailBody);
                $mailBody = str_replace("#LESSON_TIME#", Carbon::parse($lessonSchedule->lesson_starttime)->format('H:i'), $mailBody);
                $mailBody = str_replace("#LESSON_NAME#", $lessonSchedule->lesson_name, $mailBody);
                $mailBody = str_replace("#LESSON_TEXT_NAME#", $lessonSchedule->lesson_text_name, $mailBody);
                $mailBody = str_replace("#TEACHER_NICKNAME#", $lessonSchedule->teacher_nickname, $mailBody);

                Mail::raw($mailBody, function ($message) use ($lessonSchedule, $mailSubject) {
                    $message->to($lessonSchedule->teacher_email)
                        ->subject($mailSubject);
                });
            }

            if ($lessonSchedule->lang_type == LangType::JP && $getMailStudentJp) {
                $mailSubject = $getMailStudentJp[0]->mail_subject;
                $mailBody = $getMailStudentJp[0]->mail_body;
                $mailBody = str_replace("#STUDENT_NAME#", $lessonSchedule->student_name, $mailBody);
                $mailBody = str_replace("#LESSON_DATE#", Carbon::parse($lessonSchedule->lesson_date)->format('Y年m月d日'), $mailBody);
                $mailBody = str_replace("#LESSON_TIME#", Carbon::parse($lessonSchedule->lesson_starttime)->format('H:i'), $mailBody);
                $mailBody = str_replace("#LESSON_NAME#", $lessonSchedule->lesson_name, $mailBody);
                $mailBody = str_replace("#LESSON_TEXT_NAME#", $lessonSchedule->lesson_text_name, $mailBody);
                $mailBody = str_replace("#TEACHER_NICKNAME#", $lessonSchedule->teacher_nickname, $mailBody);

                Mail::raw($mailBody, function ($message) use ($lessonSchedule, $mailSubject) {
                    $message->to($lessonSchedule->student_email)
                        ->subject($mailSubject);
                });
            }

            if ($lessonSchedule->lang_type == LangType::EN && $getMailStudentEn) {
                $mailSubject = $getMailStudentEn[0]->mail_subject;
                $mailBody = $getMailStudentEn[0]->mail_body;
                $mailBody = str_replace("#STUDENT_NAME#", $lessonSchedule->student_name, $mailBody);
                $mailBody = str_replace("#LESSON_DATE#", Carbon::parse($lessonSchedule->lesson_date)->format('Y年m月d日'), $mailBody);
                $mailBody = str_replace("#LESSON_TIME#", Carbon::parse($lessonSchedule->lesson_starttime)->format('H:i'), $mailBody);
                $mailBody = str_replace("#LESSON_NAME#", $lessonSchedule->lesson_name, $mailBody);
                $mailBody = str_replace("#LESSON_TEXT_NAME#", $lessonSchedule->lesson_text_name, $mailBody);
                $mailBody = str_replace("#TEACHER_NICKNAME#", $lessonSchedule->teacher_nickname, $mailBody);

                Mail::raw($mailBody, function ($message) use ($lessonSchedule, $mailSubject) {
                    $message->to($lessonSchedule->student_email)
                        ->subject($mailSubject);
                });
            }

            if ($lessonSchedule->lang_type == LangType::ZH && $getMailStudentCn) {
                $mailSubject = $getMailStudentCn[0]->mail_subject;
                $mailBody = $getMailStudentCn[0]->mail_body;
                $mailBody = str_replace("#STUDENT_NAME#", $lessonSchedule->student_name, $mailBody);
                $mailBody = str_replace("#LESSON_DATE#", Carbon::parse($lessonSchedule->lesson_date)->format('Y年m月d日'), $mailBody);
                $mailBody = str_replace("#LESSON_TIME#", Carbon::parse($lessonSchedule->lesson_starttime)->format('H:i'), $mailBody);
                $mailBody = str_replace("#LESSON_NAME#", $lessonSchedule->lesson_name, $mailBody);
                $mailBody = str_replace("#LESSON_TEXT_NAME#", $lessonSchedule->lesson_text_name, $mailBody);
                $mailBody = str_replace("#TEACHER_NICKNAME#", $lessonSchedule->teacher_nickname, $mailBody);

                Mail::raw($mailBody, function ($message) use ($lessonSchedule, $mailSubject) {
                    $message->to($lessonSchedule->student_email)
                        ->subject($mailSubject);
                });
            }
        }
    }
}
