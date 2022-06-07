<?php

namespace App\Console\Commands;

use App\Enums\TestType;
use App\Models\SendRemindMailPattern;
use App\Models\TeacherTest;
use App\Models\TestResult;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailTestReviewRemind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_mail_test_review_remind:run';

    private $remindMailId = 665;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $remindMail = SendRemindMailPattern::find($this->remindMailId);
        $timmingMinutes = $remindMail->timming_minutes;
        //now()- 60 phút <= test.result.test_start_time + send_remind_mail_pattern.timing_minute (PHÚT) <= NOW()
        $testResults = TestResult::with('test', 'course')->whereHas('test', function ($q) {
            $q->where('test_type', TestType::ABILITY);
        })->whereNotNull('test_end_time')->whereDate('test_start_time', '>=', Carbon::now()->subMinutes(60)->subMinutes($timmingMinutes))
            ->whereDate('test_start_time', '<=', Carbon::now()->subMinutes($timmingMinutes))->get();
        $remindMailJa = $this->_getRemindMail(49, 'ja');

        foreach ($testResults as $testResult)
        {
            Log::info('send_mail_test_review_remind fot test_result id = ' . $testResult->test_result_id);
            $teachersTests = TeacherTest::with('teacher.timeZone')->where('test_id', $testResult->test->test_id)->get();
            $expiredDateEnv = (int)config('env.SKILL_TEST_REVIEW_EXPIRED_DATE');
            $expire = Carbon::parse($testResult->test_end_time)->addDays($expiredDateEnv);
            foreach ($teachersTests as $teachersTest)
            {
                Log::info('send_mail_test_review_remind fot teacher  id = ' . $teachersTest->teacher->id);

                $teacherMailSubject = $remindMailJa[0]->mail_subject;
                $teacherMailBody = $remindMailJa[0]->mail_body;
                $teacherMailBody = str_replace("#COURSE_NAME#", $testResult->course->course_name, $teacherMailBody);
                if (isset($teachersTest->teacher)) {
                    $dateTimeByTZ = $expire->setTimezone($teachersTest->teacher->timeZone->diff_time);
                    $dateByTZ = $dateTimeByTZ->format("Y/m/d");
                    $timeByTZ = $dateTimeByTZ->format('H:i');
                    $teacherMailBody = str_replace("#TEST_LIMIT_DATE#", $dateByTZ, $teacherMailBody);
                    $teacherMailBody = str_replace("#TEST_LIMIT_TIME#", " " . $timeByTZ, $teacherMailBody);
                    $teacherMailBody = str_replace("#MY_PAGE_URL#", config('env.APP_URL_TEACHER') . '/login', $teacherMailBody);
                    Mail::raw($teacherMailBody, function ($message) use ($teacherMailSubject, $teacherMailBody, $teachersTest) {
                        $message->to($teachersTest->teacher->teacher_email)
                            ->subject($teacherMailSubject);
                    });
                }

            }
        }
    }

    private function _getRemindMail($mailType, $langType = null) {
        $lang_type = !empty($langType) ? $langType : 'jp';

        $data = array(
            (int) $mailType,
            $lang_type
        );
        $mailData = DB::select('call sp_get_remindmail_by_type(?,?)',$data);
        return $mailData;
    }
}
