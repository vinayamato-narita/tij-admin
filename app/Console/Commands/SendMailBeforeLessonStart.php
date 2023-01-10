<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LessonSchedule;
use App\Models\SendRemindMailPattern;
use App\Enums\MailType;
use App\Enums\LangType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\TimeZone;
use App\Models\Lesson;
use App\Enums\CourseTypeEnum;
use DB;
use Illuminate\Support\Facades\Log;

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
        Log::info("send_mail_before_lesson_start start[start]");

        $getMailTeacher = SendRemindMailPattern::where('send_remind_mail_timing_type', MailType::MAIL_TEACHER_BEFORE_LESSON_START)->first();
        if($getMailTeacher == null) {
            return;
        }

        $timeZones = TimeZone::pluck('diff_time', 'timezone_id')->toArray();

        $getMailStudentJp = SendRemindMailPattern::getRemindmailPatternInfo(MailType::MAIL_STUDENT_BEFORE_LESSON_START, LangType::JA);
        $getMailStudentEn = SendRemindMailPattern::getRemindmailPatternInfo(MailType::MAIL_STUDENT_BEFORE_LESSON_START, LangType::EN);
        $getMailStudentCn = SendRemindMailPattern::getRemindmailPatternInfo(MailType::MAIL_STUDENT_BEFORE_LESSON_START, LangType::ZH);

        $timingMinutes = $getMailTeacher->timing_minutes;
        $timeStart = Carbon::now()->addMinutes($timingMinutes - 5);;
        $timeEnd = Carbon::now()->addMinutes($timingMinutes);
        // Log::info($timeStart->toString());
        // Log::info($timeEnd->toString());

        $lessonSchedules = LessonSchedule::select('student.student_name',
            'student.lang_type',
            'lesson_schedule.lesson_date',
            'lesson_schedule.lesson_starttime',
            'lesson.lesson_name',
            'lesson.lesson_id',
            'lesson_text.lesson_text_name',
            'teacher.teacher_nickname',
            'student.student_email',
            'lesson_schedule.lesson_schedule_id',
            'lesson_schedule.course_type',
            'lesson_schedule.zoom_url',
            'teacher.timezone_id',
            'teacher.teacher_email',
            'student.student_nickname',
            DB::raw("COALESCE(len.lesson_name, lesson.lesson_name) AS lesson_name_en"),
            DB::raw("COALESCE(lcn.lesson_name, lesson.lesson_name) AS lesson_name_cn"),
            DB::raw("COALESCE(lten.lesson_text_name, lesson_text.lesson_text_name) AS lesson_text_name_en"),
            DB::raw("COALESCE(ltcn.lesson_text_name, lesson_text.lesson_text_name) AS lesson_text_name_cn"),
            'course.course_name',
            'teacher.teacher_name',
            'student.student_id',
        )
        ->join('student_point_history', function($join) {
            $join->on('lesson_schedule.lesson_schedule_id', '=', 'student_point_history.lesson_schedule_id');
        })
        ->join('point_subscription_history', function($join) {
            $join->on('student_point_history.point_subscription_id', '=', 'point_subscription_history.point_subscription_history_id')
                ->where('point_subscription_history.payment_status', 1);
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
        ->leftJoin('lesson_info as len', function($join) {
            $join->on('lesson.lesson_id', '=', 'len.lesson_id')
            ->where('len.lang_type', LangType::EN);
        })
        ->leftJoin('lesson_info as lcn', function($join) {
            $join->on('lesson.lesson_id', '=', 'lcn.lesson_id')
            ->where('lcn.lang_type', LangType::ZH);
        })
        ->leftJoin('lesson_text_lesson', function($join) {
            $join->on('lesson.lesson_id', '=', 'lesson_text_lesson.lesson_id');
        })
        ->leftJoin('lesson_text', function($join) {
            $join->on('lesson_text_lesson.lesson_text_id', '=', 'lesson_text.lesson_text_id');
        })
        ->leftJoin('lesson_text_info as lten', function($join) {
            $join->on('lesson_text.lesson_text_id', '=', 'lten.lesson_text_id')
            ->where('lten.lang_type', LangType::EN);
        })
        ->leftJoin('lesson_text_info as ltcn', function($join) {
            $join->on('lesson_text.lesson_text_id', '=', 'ltcn.lesson_text_id')
            ->where('ltcn.lang_type', LangType::ZH);
        })
        ->join('course', function($join) {
            $join->on('lesson_schedule.course_id', '=', 'course.course_id');
        })
        ->where('lesson_schedule.course_id', '>=', 1)
        ->where('lesson_schedule.lesson_starttime', '>=', $timeStart)
        ->where('lesson_schedule.lesson_starttime', '<=', $timeEnd)
        ->orderBy('lesson_schedule.lesson_schedule_id')
        ->groupBy('lesson_schedule_id', 'student_id')
        ->get();

        $scheduleId = 0;
        Log::info("send_mail_before_lesson_start start:");
        Log::info(print_r($lessonSchedules->toArray(), true));


        foreach($lessonSchedules as $lessonSchedule) 
        {
            if ($lessonSchedule->lang_type == LangType::JA && count($getMailStudentJp)) {
                $mailSubject = $getMailStudentJp[0]->mail_subject;
                $mailBody = $getMailStudentJp[0]->mail_body;
                $mailBody = str_replace("#STUDENT_NAME#", $lessonSchedule->student_name, $mailBody);
                $mailBody = str_replace("#LESSON_DATE#", Carbon::parse($lessonSchedule->lesson_date)->format('Y-m-d'), $mailBody);
                $mailBody = str_replace("#LESSON_TIME#", Carbon::parse($lessonSchedule->lesson_starttime)->format('H:i'), $mailBody);
                $mailBody = str_replace("#LESSON_NAME#", $lessonSchedule->lesson_name, $mailBody);
                $mailBody = str_replace("#LESSON_TEXT_NAME#", $lessonSchedule->lesson_text_name, $mailBody);
                $mailBody = str_replace("#TEACHER_NICKNAME#", $lessonSchedule->teacher_nickname, $mailBody);
                $mailBody = str_replace("#STUDENT_MY_PAGE_URL#", config('env.APP_URL_STUDENT'), $mailBody);
                $mailBody = str_replace("#ZOOM_MANUAL_URL#", config('env.ZOOM_MANUAL_URL'), $mailBody);

                Mail::raw($mailBody, function ($message) use ($lessonSchedule, $mailSubject) {
                    $message->to($lessonSchedule->student_email)
                        ->subject($mailSubject);
                });
            }

            if ($lessonSchedule->lang_type == LangType::EN && count($getMailStudentEn)) {
                $mailSubject = $getMailStudentEn[0]->mail_subject;
                $mailBody = $getMailStudentEn[0]->mail_body;
                $mailBody = str_replace("#STUDENT_NAME#", $lessonSchedule->student_name, $mailBody);
                $mailBody = str_replace("#LESSON_DATE#", Carbon::parse($lessonSchedule->lesson_date)->format('Y-m-d'), $mailBody);
                $mailBody = str_replace("#LESSON_TIME#", Carbon::parse($lessonSchedule->lesson_starttime)->format('H:i'), $mailBody);
                $mailBody = str_replace("#LESSON_NAME#", $lessonSchedule->lesson_name_en, $mailBody);
                $mailBody = str_replace("#LESSON_TEXT_NAME#", $lessonSchedule->lesson_text_name_en, $mailBody);
                $mailBody = str_replace("#TEACHER_NICKNAME#", $lessonSchedule->teacher_nickname, $mailBody);
                $mailBody = str_replace("#STUDENT_MY_PAGE_URL#", config('env.APP_URL_STUDENT'), $mailBody);
                $mailBody = str_replace("#ZOOM_MANUAL_URL#", config('env.ZOOM_MANUAL_URL'), $mailBody);

                Mail::raw($mailBody, function ($message) use ($lessonSchedule, $mailSubject) {
                    $message->to($lessonSchedule->student_email)
                        ->subject($mailSubject);
                });
            }

            if ($lessonSchedule->lang_type == LangType::ZH && count($getMailStudentCn)) {
                $mailSubject = $getMailStudentCn[0]->mail_subject;
                $mailBody = $getMailStudentCn[0]->mail_body;
                $mailBody = str_replace("#STUDENT_NAME#", $lessonSchedule->student_name, $mailBody);
                $mailBody = str_replace("#LESSON_DATE#", Carbon::parse($lessonSchedule->lesson_date)->format('Y-m-d'), $mailBody);
                $mailBody = str_replace("#LESSON_TIME#", Carbon::parse($lessonSchedule->lesson_starttime)->format('H:i'), $mailBody);
                $mailBody = str_replace("#LESSON_NAME#", $lessonSchedule->lesson_name_cn, $mailBody);
                $mailBody = str_replace("#LESSON_TEXT_NAME#", $lessonSchedule->lesson_text_name_cn, $mailBody);
                $mailBody = str_replace("#TEACHER_NICKNAME#", $lessonSchedule->teacher_nickname, $mailBody);
                $mailBody = str_replace("#STUDENT_MY_PAGE_URL#", config('env.APP_URL_STUDENT'), $mailBody);
                $mailBody = str_replace("#ZOOM_MANUAL_URL#", config('env.ZOOM_MANUAL_URL'), $mailBody);

                Mail::raw($mailBody, function ($message) use ($lessonSchedule, $mailSubject) {
                    $message->to($lessonSchedule->student_email)
                        ->subject($mailSubject);
                });
            }

            if ($scheduleId == $lessonSchedule->lesson_schedule_id) {
                continue;
            }
            $mailSubject = $getMailTeacher->mail_subject;
            $mailBody = $getMailTeacher->mail_body;
            $mailBody = str_replace("#TEACHER_NAME#", $lessonSchedule->teacher_name, $mailBody);
            $mailBody = str_replace("#TEACHER_NICKNAME#", $lessonSchedule->teacher_nickname, $mailBody);
            $mailBody = str_replace("#LESSON_NAME#", $lessonSchedule->lesson_name, $mailBody);
            $mailBody = str_replace("#COURSE_NAME#", $lessonSchedule->course_name, $mailBody);

            $diff_time = $timeZones[$lessonSchedule->timezone_id];

            $mailBody = str_replace("#LESSON_DATE#", $this->getDateTimeZone($lessonSchedule->lesson_starttime, $diff_time), $mailBody);
            $mailBody = str_replace("#LESSON_TIME#", $this->getTimeTimeZone($lessonSchedule->lesson_starttime, $diff_time), $mailBody);
            $mailBody = str_replace("#LESSON_DATE_JP#", Carbon::parse($lessonSchedule->lesson_date)->format('Y-m-d'), $mailBody);
            $mailBody = str_replace("#LESSON_TIME_JP#", Carbon::parse($lessonSchedule->lesson_starttime)->format('H:i'), $mailBody);
            $mailBody = str_replace("#LESSON_TEXT_NAME#", $lessonSchedule->lesson_text_name, $mailBody);
            $mailBody = str_replace("#ZOOM_URL#", $lessonSchedule->zoom_url, $mailBody);
            $mailBody = str_replace("#TEACHER_MY_PAGE_URL#", config('env.APP_URL_TEACHER'), $mailBody);

            if ($lessonSchedule->course_type == CourseTypeEnum::REGULAR_COURSE) {
                $mailBody = str_replace("#STUDENT_NICKNAME#", $lessonSchedule->student_nickname, $mailBody);
            }else {
                $mailBody = str_replace("#STUDENT_NICKNAME#", '-', $mailBody);
            }
            Mail::raw($mailBody, function ($message) use ($lessonSchedule, $mailSubject) {
                $message->to($lessonSchedule->teacher_email)
                    ->subject($mailSubject);
            });
            $scheduleId = $lessonSchedule->lesson_schedule_id;
        }
        Log::info("send_mail_before_lesson_start start[end]");

    }

    private function getDateTimeZone($date, $diff) 
    {
        $dif = ($diff - 9)  * 60 * 60;
        $timestamp = strtotime($date) + $dif;
        return date('Y年m月d日', $timestamp);
    }

    private function getTimeTimeZone($date, $diff) 
    {
        $dif = ($diff - 9)  * 60 * 60;
        $timestamp = strtotime($date) + $dif;
        return date('H:i', $timestamp);
    }
}
