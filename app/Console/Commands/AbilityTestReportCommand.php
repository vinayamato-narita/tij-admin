<?php

namespace App\Console\Commands;

use App\Models\TestResult;
use App\Models\TestTopScore;
use Carbon\Carbon;
use Illuminate\Console\Command;
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
        $tests = TestResult::where('test_end_time', '=', Carbon::yesterday())->where('is_passed', true)->get();
        if (!empty($tests)) {
            foreach ($tests as $test) {
                // by test_category_name
                $data = TestResult::selectRaw("test_category.test_parrent_category_name, test_category.test_category_id, test_category.category_name, avg(test_sub_question.score) as avg_score")->join('test_result_detail', 'test_result_detail.test_result_id', '=', 'test_result.test_result_id')
                    ->join('test_sub_question', 'test_result_detail.test_sub_question_id', '=', 'test_sub_question.test_sub_question_id')
                    ->join('test_sub_question_category', 'test_sub_question_category.test_sub_question_id', '=', 'test_sub_question.test_sub_question_id')
                    ->join('test_category', 'test_category.test_category_id', '=', 'test_sub_question_category.test_category_id')
                    ->where('test_result.test_id', $test->test_id)->where('test_result_detail.answer', 'test_result_detail.correct_answer')
                    ->groupBy('test_category.test_parrent_category_name', 'test_category.test_category_id', 'test_category.category_name')
                    ->get();
                // by test_parent_category_name
                $data2 = TestResult::selectRaw("test_category.test_parrent_category_name, avg(test_sub_question.score) as avg_score")->join('test_result_detail', 'test_result_detail.test_result_id', '=', 'test_result.test_result_id')
                    ->join('test_sub_question', 'test_result_detail.test_sub_question_id', '=', 'test_sub_question.test_sub_question_id')
                    ->join('test_sub_question_category', 'test_sub_question_category.test_sub_question_id', '=', 'test_sub_question.test_sub_question_id')
                    ->join('test_category', 'test_category.test_category_id', '=', 'test_sub_question_category.test_category_id')
                    ->where('test_result.test_id', $test->test_id)->where('test_result_detail.answer', 'test_result_detail.correct_answer')
                    ->groupBy('test_category.test_parrent_category_name')
                    ->get();

                TestTopScore::updateOrCreate(
                    ['test_id' => !empty($data['test_id']) ? $data['test_id'] : null, 'test_parent_name' => !empty($data['test_parent_name']) ? $data['test_parent_name'] : null, 'test_category_id' => !empty($data['test_category_id']) ? $data['test_category_id'] : null],
                    ['category_name' => $data['category_name'], 'top_score_avg' => $data['avg_score']]
                );

                TestTopScore::updateOrCreate(
                    ['test_id' => !empty($data2['test_id']) ? $data2['test_id'] : null, 'test_parent_name' => !empty($data2['test_parent_name']) ? $data2['test_parent_name'] : null],
                    ['test_category_id' => null, 'category_name' => null, 'top_score_avg' => $data2['avg_score']]
                );
            }
        }
        return 0;
    }
}
