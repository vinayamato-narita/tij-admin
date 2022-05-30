<?php

namespace App\Console\Commands;

use App\Enums\TestType;
use App\Models\TeacherTest;
use App\Models\TestResult;
use App\Models\TestResultDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DailyUpdateTestResultNotSubmitted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily_update_test_result_not_submitted:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'daily update test result when go to test , but go out and never go again';

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

        Log::info('---start run: daily_update_test_result_not_submitted:run---');
        $testResults = DB::table('test_result')
            ->join('test', 'test_result.test_id', '=', 'test.test_id')
            ->whereNull('test_end_time')
            ->whereRaw('NOW() >= date_add(test_start_time ,interval test.execution_time minute)')
            ->whereIn('test_type', [TestType::ABILITY, TestType::ENDCOURSE])
            ->get();
        foreach ($testResults as $testResultStd) {
            Log::info('--- handle with test_result_id = ' . $testResultStd->test_result_id);

            $testResult = TestResult::with('test', 'course')->find($testResultStd->test_result_id);
            $testResult->test_end_time = Carbon::parse($testResult->test_start_time)->addMinutes($testResult->execution_time);
            $resultTotalScore = TestResultDetail::with('testSubQuestion')->where([
                'test_result_id' => $testResult->test_result_id,
            ])->whereColumn('answer', 'like', 'correct_answer')
                ->join('test_sub_question', 'test_sub_question.test_sub_question_id', 'test_result_detail.test_sub_question_id')
                ->selectRaw('test_sub_question.score')
                ->sum('test_sub_question.score');
            if ($resultTotalScore >= $testResult->test->passing_score)
                $testResult->is_passed = 1;
            if (in_array($testResult->test->test_type, [TestType::ABILITY, TestType::ENDCOURSE]))
                $testResult->is_reviewed = false;
            if ($testResult->test->test_type === TestType::ABILITY) {
                $teachersTests = TeacherTest::with('teacher.timeZone')->where('test_id', $testResult->test->test_id)->get();
                $expire = Carbon::now()->addDays(env('SKILL_TEST_REVIEW_EXPIRED_DATE'));

            if ($testResult->save())
                foreach ($teachersTests as $teachersTest) {
                    $mailDataStudent = $this->_getRemindMail(48, null);
                    if (!empty($mailDataStudent)) {
                        $teacherMailSubject = $mailDataStudent[0]->mail_subject;
                        $teacherMailBody = $mailDataStudent[0]->mail_body;
                        $teacherMailBody = str_replace("#COURSE_NAME#", $testResult->course->course_name, $teacherMailBody);
                        if (isset($teachersTest->teacher)) {
                            $dateTimeByTZ = $expire->setTimezone($teachersTest->teacher->timeZone->diff_time);
                            $dateByTZ = $dateTimeByTZ->format("Y/m/d");
                            $timeByTZ = $dateTimeByTZ->format('H:i');
                            $teacherMailBody = str_replace("#TEST_LIMIT_DATE#", $dateByTZ, $teacherMailBody);
                            $teacherMailBody = str_replace("#TEST_LIMIT_TIME#", " " . $timeByTZ, $teacherMailBody);
                            $teacherMailBody = str_replace("#MY_PAGE_URL#", env('APP_URL_TEACHER') . '/login', $teacherMailBody);
                            Mail::raw($teacherMailBody, function ($message) use ($teacherMailSubject, $teacherMailBody, $teachersTest) {
                                $message->to($teachersTest->teacher->teacher_email)
                                    ->subject($teacherMailSubject);
                            });
                        }


                    }

                }


            }
        }

        return Command::SUCCESS;
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
