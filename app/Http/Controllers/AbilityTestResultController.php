<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Enums\TestType;
use App\Models\TestCategory;
use App\Models\TestComment;
use App\Models\TestQuestion;
use App\Models\TestResult;
use App\Models\TestResultDetail;
use App\Models\TestSubQuestion;
use App\Models\TestTopScore;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class AbilityTestResultController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'ability_test_result_list']
        ]);
        $pageLimit = $this->newListLimit($request);

        $queryBuilder = TestResult::select('student.student_id',
            'student.student_name',
            'test.test_name',
            'test_result.test_start_time',
            'test_result.test_result_id',
            'test_comment.comment_start_time',
            'test_comment.comment_end_time',
            DB::raw('(CASE
                WHEN test_comment.comment_start_time IS Null THEN "評価待ち"
                WHEN test_comment.comment_end_time IS Null AND test_comment.comment_start_time IS NOT NULL THEN "評価中"
                ELSE "済" 
                END
            )AS status')
        )
        ->join('student', function($join) {
            $join->on('test_result.student_id', '=', 'student.student_id');
        })
        ->join('test', function($join) {
            $join->on('test_result.test_id', '=', 'test.test_id')
                ->where('test.test_type', TestType::ABILITY);
        })
        ->leftJoin('test_comment', function($join) {
            $join->on('test_result.test_result_id', '=', 'test_comment.test_result_id');
        });

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student.student_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('test.test_name', $request['search_input']));
            });
        }

        if (isset($request['student_id'])) {
            if ($request['student_id'] != "") {
                $queryBuilder = $queryBuilder->where('student.student_id', $request['student_id']);
            }
            $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('student.student_name', $request['student_name']))
            ->where($this->escapeLikeSentence('test_name', $request['test_name']));
        }

        if (!empty($request['status'])) {
            switch ($request['status']) {
                case 'WAITING_EVALUATION':
                    $queryBuilder = $queryBuilder->whereNull('test_comment.comment_start_time');
                    break;

                case 'UNDER_EVALUATION' :
                    $queryBuilder = $queryBuilder->whereNull('test_comment.comment_end_time')->whereNotNull('test_comment.comment_start_time');
                    break;
                case 'ALREADY':
                    $queryBuilder = $queryBuilder->whereNotNull('test_comment.comment_end_time');
                    break;

            }
        }

        if (isset($request['sort'])) {
            if ($request['sort'] == "custom_status") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('status','ASC') : $queryBuilder->orderBy('status','DESC');
            }
            if ($request['sort'] == "comment_start_time") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('comment_start_time','ASC') : $queryBuilder->orderBy('comment_start_time','DESC');
            }
            if ($request['sort'] == "test_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('test_name','ASC') : $queryBuilder->orderBy('test_name','DESC');
            }
            if ($request['sort'] == "student_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_name','ASC') : $queryBuilder->orderBy('student_name','DESC');
            }
        }

        $testResultList = $queryBuilder->sortable(['last_update_date' => 'desc'])->paginate($pageLimit);

        return view('abilityTestResult.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'testResultList' => $testResultList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'ability_test_result_list'],
            ['name' => 'ability_test_result_show', $id]
        ]);

        $testResult = TestResult::with('student', 'test', 'test_comment')->find($id);
        if (!$testResult)
            return redirect()->route('abilityTestResult.index');
        $parentCategoryNames = TestCategory::orderBy('display_order', 'asc')->groupBy('parent_category_name')->pluck('parent_category_name')->toArray();
        $commentIndexs = ['comment1', 'comment2', 'comment3', 'comment4', 'comment5'];
        $comments = [];
        foreach ($parentCategoryNames as $index => $value) {
            $propName = $commentIndexs[$index];
            $comments[] = [
                'title' => $value,
                'comment_desc' => empty($testResult->test_comment) ? '' : $testResult->test_comment->$propName
            ];
        }
        $disableComment = false;
        $now = Carbon::now();

        if (!empty($testResult->test_comment) && $testResult->test_comment->comment_end_time === null && $now->diffInHours($testResult->test_comment->comment_start_time) <= env('MAX_HOURS_COMMENT') && $testResult->test_comment->teacher_admin_id != Auth::user()->admin_user_id)
            $disableComment = true;

        return view('abilityTestResult.show', [
            'testResult' => $testResult,
            'comments' => $comments,
            'breadcrumbs' => $breadcrumbs,
            'disableComment' => $disableComment
        ]);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'ability_test_result_list'],
            ['name' => 'ability_test_result_show', $id],
            ['name' => 'ability_test_result_edit', $id]
        ]);
        $testResult = TestResult::with('student', 'test', 'test_comment')->find($id);
        if (!$testResult)
            return redirect()->route('abilityTestResult.index');

        $analyticList = [];
        $tcParents = TestCategory::get()->groupBy('parent_category_name')->toArray();
        foreach ($tcParents as $parentIndex => $tcParent) {
            $addItems = [];
            foreach ($tcParent as $tc) {

                $testSubQuestionsCount = TestSubQuestion::with('testCategories', 'testQuestion')->where([
                ])->whereHas('testCategories', function ($q) use ($tc) {
                    return $q->where('test_sub_question_category.test_category_id', $tc['test_category_id']);
                })->whereHas('testQuestion', function ($q) use ($testResult) {
                    return $q->where('test_id', $testResult->test->test_id);

                })->get()->count();

                $sumScore = TestResultDetail::with('testSubQuestion.testCategories')->whereHas('testSubQuestion',
                    function ($q) use ($tc) {
                        $q->whereHas('testCategories', function ($q) use ($tc) {
                            return $q->where('test_sub_question_category.test_category_id', $tc['test_category_id']);

                        });
                    })->where('test_result_id', $id)->whereColumn('answer', '=', 'correct_answer')->withSum('testSubQuestion', 'score')->get();

                $sumTotalScore = TestResultDetail::with('testSubQuestion.testCategories')->whereHas('testSubQuestion',
                    function ($q) use ($tc) {
                        $q->whereHas('testCategories', function ($q) use ($tc) {
                            return $q->where('test_sub_question_category.test_category_id', $tc['test_category_id']);

                        });
                    })->where('test_result_id', $id)->withSum('testSubQuestion', 'score')->get();

                $topScore = TestTopScore::where([
                    'test_id' => $testResult->test->test_id,
                    'test_category_id' => $tc['test_category_id']
                ])->first();
                $addItems[] = [
                    'category_name' => $tc['category_name'],
                    'parent_category_name' => $tc['parent_category_name'],
                    'num_sub_question' => empty($testSubQuestionsCount) ? '-' : $testSubQuestionsCount,
                    'exam_score' => empty($testSubQuestionsCount) ? '-' : $sumScore->sum('test_sub_question_sum_score'),
                    'score' => empty($testSubQuestionsCount) ? '-' : $sumTotalScore->sum('test_sub_question_sum_score'),
                    'top_score_avg' => empty($testSubQuestionsCount) || empty($topScore) ? '-' : $topScore->top_score_avg

                ];

            }
            $analyticList[] = $addItems;


        }

        $testComment = $testResult->test_comment;
        $disableComment = false;
        if (empty($testComment)) {
            $testComment = new TestComment();
            $testComment->test_result_id = $id;
            $testComment->student_id = $testResult->student_id;
            $testComment->teacher_admin_id = Auth::user()->admin_user_id;
            $testComment->comment_start_time = Carbon::now();
            if (!$testComment->save())
                return redirect()->route('abilityTestResult.show', $id);
        } else {
            $now = Carbon::now();
            if ($testComment->comment_end_time === null && $now->diffInHours($testComment->comment_start_time) > env('MAX_HOURS_COMMENT')) {
                $testComment->comment_start_time = Carbon::now();
                $testComment->teacher_admin_id = Auth::user()->admin_user_id;
                if (!$testComment->save())
                    return redirect()->route('abilityTestResult.show', $id);
            }

            if ($testComment->comment_end_time === null && $now->diffInHours($testComment->comment_start_time) <= env('MAX_HOURS_COMMENT') && $testComment->teacher_admin_id != Auth::user()->admin_user_id)
                $disableComment = true;
        }

        $parentCategoryNames = TestCategory::orderBy('display_order', 'asc')->groupBy('parent_category_name')->pluck('parent_category_name')->toArray();
        $commentIndexs = ['comment1', 'comment2', 'comment3', 'comment4', 'comment5'];
        $comments = [];
        foreach ($parentCategoryNames as $index => $value) {
            $propName = $commentIndexs[$index];
            $comments[] = [
                'title' => $value,
                'comment_desc' => empty($testResult->test_comment) ? '' : $testResult->test_comment->$propName,
                'input_name' => $propName
            ];
        }

        return view('abilityTestResult.edit', [
            'testResult' => $testResult,
            'testComment' => $testComment,
            'disableComment' => $disableComment,
            'breadcrumbs' => $breadcrumbs,
            'analyticList' => $analyticList,
            'comments' => $comments,

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTestComment(Request $request, $id)
    {
        $testComment = TestComment::with('testResult')->find($id);
        if (!$testComment)
            return redirect()->route('abilityTestResult.index');
        $testComment->teacher_admin_id = Auth::user()->admin_user_id;
        $testComment->comment_end_time = Carbon::now();
        $testComment->comment1 = $request->comment1;
        $testComment->comment2 = $request->comment2;
        $testComment->comment3 = $request->comment3;
        $testComment->comment4 = $request->comment4;
        $testComment->comment5 = $request->comment5;

        $testComment->testResult->is_reviewed = true;

        if ($testComment->save() && $testComment->testResult->save())
            return response()->json([
                'status' => 'OK',
            ], StatusCode::OK);
        return response()->json([
            'status' => 'INTERNAL_ERR',
        ], StatusCode::INTERNAL_ERR);

    }

    public function answerDetail($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'ability_test_result_list'],
            ['name' => 'ability_test_result_show', $id],
            ['name' => 'ability_test_result_edit', $id],
            ['name' => 'ability_test_result_answer_detail', $id]
        ]);
        $testResult = TestResult::with('test')->find($id);
        if (!$testResult)
            return redirect()->route('abilityTestResult.index');


        $testQuestions = TestQuestion::with('testSubQuestions.file')->where('test_id', $testResult->test_id)->orderBy('display_order')->get()->toArray();
        foreach ($testQuestions as &$testQuestion) {
            $testQuestion['is_answered'] = TestResultDetail::where([
                'test_result_id' => $id,
                'test_question_id' => $testQuestion['test_question_id']
            ])->whereNotNull('answer')->exists();
            foreach ($testQuestion['test_sub_questions'] as &$tsqs) {
                $tR = TestResultDetail::where([
                    'test_result_id' => $id,
                    'test_sub_question_id' => $tsqs['test_sub_question_id'],
                ])->first();
                if ($tR) {
                    $tsqs['is_right'] =  $tR->answer == $tR->correct_answer;
                    $tsqs['choiced_answer'] = $tR->answer;
                }
                else {
                    $tsqs['is_right'] =  false;
                    $tsqs['choiced_answer'] = null;
                }
                if (!empty($tsqs['answer1'])) $tsqs['answers'][] = $tsqs['answer1'];
                if (!empty($tsqs['answer2'])) $tsqs['answers'][] = $tsqs['answer2'];
                if (!empty($tsqs['answer3'])) $tsqs['answers'][] = $tsqs['answer3'];
                if (!empty($tsqs['answer4'])) $tsqs['answers'][] = $tsqs['answer4'];
            }

        }

        return view('abilityTestResult.answerDetail', [
            'breadcrumbs' => $breadcrumbs,
            'testResult' => $testResult,
            'testQuestions' => $testQuestions,

        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
