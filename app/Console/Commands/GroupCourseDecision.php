<?php

namespace App\Console\Commands;

use App\Enums\CourseTypeEnum;
use App\Enums\GroupLessonStatus;
use App\Enums\MailType;
use App\Enums\JobCdType;
use App\Enums\PaymentWay;
use App\Enums\PaymentStatus;
use App\Models\Course;
use App\Models\LessonHistory;
use App\Models\LessonSchedule;
use App\Models\PointSubscriptionHistory;
use App\Models\SendRemindMailPattern;
use App\Models\StudentPointHistory;
use App\Services\GmoService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Enums\LangType;

class GroupCourseDecision extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group_course_decision:run';

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
        $gmoService = new GmoService();

        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $courses = Course::whereDate('decide_date', $yesterday)->where('course_type', CourseTypeEnum::GROUP_COURSE)->where('group_lesson_status', GroupLessonStatus::BEFORE_DECIDE)->where('is_for_lms', 0)->with(['pointSubscriptionHistories', 'pointSubscriptionHistories.student', 'lessonSchedules', 'lessonSchedules.teacher', 'lessonSchedules.teacher.teacherInfo'])->get();
        $cancelCourseIds = [];
        $decideCourseIds = [];

        $getMailStudentJa = SendRemindMailPattern::getRemindmailPatternInfo(MailType::OPEN_GROUP_LESSON, LangType::JA);
        $getMailStudentEn = SendRemindMailPattern::getRemindmailPatternInfo(MailType::OPEN_GROUP_LESSON, LangType::EN);
        $getMailStudentZh = SendRemindMailPattern::getRemindmailPatternInfo(MailType::OPEN_GROUP_LESSON, LangType::ZH);

        foreach ($courses as $course) {
            try {
                // remove duplicated pointSubscriptionHistory
                $pointSubscriptionHistories = [];
                foreach ($course->pointSubscriptionHistories as $p) {
                    if ($p->payment_status == PaymentStatus::SUCCESS) {
                        $pointSubscriptionHistories[] = $p;
                    }
                }

                // calculate reserved students
                $reserveNum = count($pointSubscriptionHistories);

                if ($reserveNum < $course->min_reserve_count) {
                    $cancelCourseIds[] = $course->course_id;

                    foreach ($pointSubscriptionHistories as $pointSubscriptionHistory) {
                        // cancel order
                        try {
                            Log::info("cancel order id:".$pointSubscriptionHistory->order_id);
                            $order = null;
                            if ($pointSubscriptionHistory->payment_way == PaymentWay::CREDIT) {
                                $order = $gmoService->searchTrade($pointSubscriptionHistory->order_id);
                            } elseif ($pointSubscriptionHistory->payment_way == PaymentWay::PAYPAL) {
                                $order = $gmoService->searchTradeMulti($pointSubscriptionHistory->order_id);
                            }

                            if (!empty($order)) {
                                $result = false;
                                if ($pointSubscriptionHistory->payment_way == PaymentWay::CREDIT) {
                                    $result = $gmoService->alterTran(JobCdType::CANCEL, $order["AccessID"], $order["AccessPass"], $order["Amount"]);
                                } elseif ($pointSubscriptionHistory->payment_way == PaymentWay::PAYPAL) {
                                    $result = $gmoService->cancelTranPaypal($pointSubscriptionHistory->order_id, $order["AccessID"], $order["AccessPass"], $order["Amount"] + $order["Tax"]);
                                }

                                if (!empty($result)) {
                                    Log::info("cancel order success");
                                }
                            }
                        } catch (\Exception $e) {
                            Log::info("cancel order id error:".$pointSubscriptionHistory->order_id);
                            Log::info($e->getMessage());
                        }

                        //send mail student
                        $studentName = $pointSubscriptionHistory->student->student_name;

                        $langType = $pointSubscriptionHistory->student->lang_type;
                        $courseInfo = $course->course_infos->where('lang_type', $langType)->first();

                        $mailPattern = SendRemindMailPattern::getRemindmailPatternInfo(MailType::STUDENT_CANCEL_LESSON, $langType);

                        if (count($mailPattern)) {
                            $mailSubject = $mailPattern[0]->mail_subject;
                            $mailBody = $mailPattern[0]->mail_body;
                            $mailBody = str_replace("#STUDENT_NAME#", $studentName, $mailBody);
                            $mailBody = str_replace("#COURSE_NAME#", !empty($courseInfo) ? $courseInfo->course_name : $course->course_name, $mailBody);

                            Mail::raw($mailBody, function ($message) use ($pointSubscriptionHistory, $mailSubject) {
                                $message->to($pointSubscriptionHistory->student->student_email)
                                    ->subject($mailSubject);
                            });
                        }
                    }

                    //send mail teacher
                    foreach ($course->lessonSchedules as $lessonSchedule) {
                        $mailPattern = SendRemindMailPattern::getRemindmailPatternInfo(MailType::TEACHER_CANCEL_LESSON, LangType::JA);

                        if (count($mailPattern)) {
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

                    foreach ($pointSubscriptionHistories as $pointSubscriptionHistory) {
                        // update sales order
                        try {
                            Log::info("update sales order id:".$pointSubscriptionHistory->order_id);
                            $order = null;
                            if ($pointSubscriptionHistory->payment_way == PaymentWay::CREDIT) {
                                $order = $gmoService->searchTrade($pointSubscriptionHistory->order_id);
                            } elseif ($pointSubscriptionHistory->payment_way == PaymentWay::PAYPAL) {
                                $order = $gmoService->searchTradeMulti($pointSubscriptionHistory->order_id);
                            }

                            if (!empty($order)) {
                                $result = false;
                                if ($pointSubscriptionHistory->payment_way == PaymentWay::CREDIT) {
                                    $result = $gmoService->alterTran(JobCdType::SALES, $order["AccessID"], $order["AccessPass"], $order["Amount"]);
                                } elseif ($pointSubscriptionHistory->payment_way == PaymentWay::PAYPAL) {
                                    $result = $gmoService->paypalSales($pointSubscriptionHistory->order_id, $order["AccessID"], $order["AccessPass"], $order["Amount"] + $order["Tax"]);
                                }

                                if (!empty($result)) {
                                    PointSubscriptionHistory::where('order_id', '=', $pointSubscriptionHistory->order_id)
                                        ->update(['receive_payment_date' => Carbon::now()]);
                                    Log::info("update sales order success");

                                    //send mail student
                                    $studentName = $pointSubscriptionHistory->student->student_name;
                                    $studentEmail = $pointSubscriptionHistory->student->student_email;

                                    $langType = $pointSubscriptionHistory->student->lang_type;
                                    $courseInfo = $course->course_infos->where('lang_type', $langType)->first();
                                    $courseName = !empty($courseInfo) ? $courseInfo->course_name : $course->course_name;

                                    if ($langType == LangType::JA && count($getMailStudentJa)) {
                                        $mailSubject = $getMailStudentJa[0]->mail_subject;
                                        $mailBody = $getMailStudentJa[0]->mail_body;
                                        $mailBody = str_replace("#STUDENT_NAME#", $studentName, $mailBody);
                                        $mailBody = str_replace("#COURSE_NAME#", $courseName, $mailBody);
                                        $mailBody = str_replace("#STUDENT_MY_PAGE_URL#", config('env.APP_URL_STUDENT'), $mailBody);

                                        Mail::raw($mailBody, function ($message) use ($studentEmail, $mailSubject) {
                                            $message->to($studentEmail)
                                                ->subject($mailSubject);
                                        });
                                    }

                                    if ($langType == LangType::EN && count($getMailStudentEn)) {
                                        $mailSubject = $getMailStudentEn[0]->mail_subject;
                                        $mailBody = $getMailStudentEn[0]->mail_body;
                                        $mailBody = str_replace("#STUDENT_NAME#", $studentName, $mailBody);
                                        $mailBody = str_replace("#COURSE_NAME#", $courseName, $mailBody);
                                        $mailBody = str_replace("#STUDENT_MY_PAGE_URL#", config('env.APP_URL_STUDENT'), $mailBody);

                                        Mail::raw($mailBody, function ($message) use ($studentEmail, $mailSubject) {
                                            $message->to($studentEmail)
                                                ->subject($mailSubject);
                                        });
                                    }

                                    if ($langType == LangType::ZH && count($getMailStudentZh)) {
                                        $mailSubject = $getMailStudentZh[0]->mail_subject;
                                        $mailBody = $getMailStudentZh[0]->mail_body;
                                        $mailBody = str_replace("#STUDENT_NAME#", $studentName, $mailBody);
                                        $mailBody = str_replace("#COURSE_NAME#", $courseName, $mailBody);
                                        $mailBody = str_replace("#STUDENT_MY_PAGE_URL#", config('env.APP_URL_STUDENT'), $mailBody);

                                        Mail::raw($mailBody, function ($message) use ($studentEmail, $mailSubject) {
                                            $message->to($studentEmail)
                                                ->subject($mailSubject);
                                        });
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            Log::info("update sales order id error:".$pointSubscriptionHistory->order_id);
                            Log::info($e->getMessage());
                        }
                    }
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
