<?php

namespace App\Console\Commands;

use App\Models\TestResult;
use App\Models\TestTopScore;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Log;

class AbilityTestReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ability_test_report:run';

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
        try {
            $this->info("ability_test_report start");
            $tests = TestResult::whereDate('test_end_time', Carbon::yesterday()->format("Y-m-d"))
                ->where("is_passed", true)
                ->get();
            if (!empty($tests)) {
                foreach ($tests as $test) {
                    // by test_category_name
                    $data = TestResult::selectRaw("test_category.test_category_id, test_category.category_name, sum(test_sub_question.score) as total_score,count(distinct(test_result_detail.test_result_id)) as test_time")
                        ->join('test_result_detail', 'test_result_detail.test_result_id', '=', 'test_result.test_result_id')
                        ->join('test_sub_question', 'test_result_detail.test_sub_question_id', '=', 'test_sub_question.test_sub_question_id')
                        ->join('test_sub_question_category', 'test_sub_question_category.test_sub_question_id', '=', 'test_sub_question.test_sub_question_id')
                        ->join('test_category', 'test_category.test_category_id', '=', 'test_sub_question_category.test_category_id')
                        ->where('test_result.test_id', $test->test_id)
                        ->where('test_result.is_passed', true)
                        ->whereRaw('test_result_detail.answer = test_result_detail.correct_answer')
                        ->groupBy('test_category.test_category_id', 'test_category.category_name')
                        ->get()
                        ->toArray();

                    // by test_parent_category_name
                    $data2 = TestResult::selectRaw("test_category.parent_category_name, SUM(test_sub_question.score) AS total_score ,COUNT(DISTINCT(test_result_detail.test_result_id)) AS test_time")
                        ->join('test_result_detail', 'test_result_detail.test_result_id', '=', 'test_result.test_result_id')
                        ->join('test_sub_question', 'test_result_detail.test_sub_question_id', '=', 'test_sub_question.test_sub_question_id')
                        ->join('test_sub_question_category', 'test_sub_question_category.test_sub_question_id', '=', 'test_sub_question.test_sub_question_id')
                        ->join('test_category', 'test_category.test_category_id', '=', 'test_sub_question_category.test_category_id')
                        ->where('test_result.test_id', $test->test_id)
                        ->whereRaw('test_result_detail.answer = test_result_detail.correct_answer')
                        ->where('test_result.is_passed', true)
                        ->groupBy('test_category.parent_category_name')
                        ->get()
                        ->toArray();

                    // update test top score by data
                    if ($data) {
                        foreach ($data as $item) {
                            TestTopScore::updateOrCreate(
                                [
                                    'test_id' => $test->test_id,
                                    'test_parrent_name' => null,
                                    'test_category_id' => !empty($item['test_category_id']) ? $item['test_category_id'] : null
                                ],
                                [
                                    'category_name' => $item['category_name'],
                                    'top_score_avg' => $item['total_score'] / $item['test_time']
                                ]
                            );
                        }
                    }


                    // update test top score by data2
                    if ($data2) {
                        foreach ($data2 as $item) {
                            TestTopScore::updateOrCreate(
                                [
                                    'test_id' => $test->test_id,
                                    'test_parrent_name' => !empty($item['parent_category_name']) ? $item['parent_category_name'] : null,
                                    'test_category_id' => null
                                ],
                                [
                                    'category_name' => null,
                                    'top_score_avg' => (int)$item['total_score'] / (int)$item['test_time']
                                ]
                            );
                        }
                    }
                }
            }
            $this->info("ability_test_report done");
        } catch (Exception $exception) {
            $this->error("error");
            Log::info($exception);
        }
    }
}
