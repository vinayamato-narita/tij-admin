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
use App\Models\TestTopScore;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $queryBuilder = (new TestResult())::with('student', 'test', 'test_comment');

        if (!empty($request['search_input'])) {
            $queryBuilder = $queryBuilder->whereHas('student', function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student_name', $request['search_input']));
            })->orWhereHas('test', function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('test_name', $request['search_input']));
            });
        }

        if (!empty($request['student_id'])) {
            $queryBuilder = $queryBuilder->whereHas('student', function ($query) use ($request) {
                $query->where('student_id', $request['student_id']);
            });
        }

        if (!empty($request['student_name'])) {
            $queryBuilder = $queryBuilder->whereHas('student', function ($query) use ($request) {
                $query->where('student_name', $request['student_name']);
            });
        }

        if (!empty($request['test_name'])) {
            $queryBuilder = $queryBuilder->whereHas('test', function ($query) use ($request) {
                $query->where('test_name', $request['test_name']);
            });
        }

        if (!empty($request['test_name'])) {
            $queryBuilder = $queryBuilder->whereHas('test', function ($query) use ($request) {
                $query->where('test_name', $request['test_name']);
            });
        }

        if (!empty($request['test_start_time_form']) || !empty($request['test_start_time_to'])) {
            $from = Carbon::createFromTimestamp($request['test_start_time_form']);
            $to = Carbon::createFromTimestamp($request['test_start_time_to']);
            if (!empty($request['test_start_time_form']) && !empty($request['test_start_time_to'])) {
                $queryBuilder = $queryBuilder->whereBetween('test_start_time', [$from, $to]);
            }
            else {
                if (!empty($request['test_start_time_form']))
                    $queryBuilder = $queryBuilder->whereDate('test_start_time', '>=', $from);
                if (!empty($request['test_start_time_to']))
                    $queryBuilder = $queryBuilder->whereDate('test_start_time', '<=', $to);
            }
        }

        if (!empty($request['status'])) {
            switch ($request['status']) {
                case 'WAITING_EVALUATION':
                    $queryBuilder = $queryBuilder->doesntHave('test_comment');
                    break;

                case 'UNDER_EVALUATION' :
                    $queryBuilder = $queryBuilder->whereHas('test_comment', function ($query) use ($request) {
                        $query->whereNull('comment_end_time');
                    });
                    break;
                case 'ALREADY':
                    $queryBuilder = $queryBuilder->whereHas('test_comment', function ($query) use ($request) {
                        $query->whereNotNull('comment_end_time');
                    });
                    break;

            }
        }


        $testResultList = $queryBuilder->whereHas('test', function ($q) {
            return $q->where('test_type', TestType::ABILITY);
        })->sortable(['last_update_date' => 'desc'])->paginate($pageLimit);

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
        return view('abilityTestResult.show', [
            'testResult' => $testResult,
            'comments' => $comments,
            'breadcrumbs' => $breadcrumbs,
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

        $analyticRaw = DB::select("
        		SELECT tc.parent_category_name, tc.category_name,tq.navigation,tq.test_question_id, tsqs.test_sub_question_id, IF(trd.answer = trd.correct_answer, tsq.score, 0) as exam_score,
				tsq.score as score, tc.test_category_id
				
        FROM test_category as tc 
				LEFT JOIN test_sub_question_category AS tsqs ON tc.test_category_id = tsqs.test_category_id 
				LEFT JOIN test_sub_question AS tsq ON tsqs.test_sub_question_id = tsq.test_sub_question_id
				LEFT JOIN test_question AS tq ON tq.test_question_id = tsq.test_question_id 
				LEFT JOIN test_result_detail AS trd ON trd.test_sub_question_id = tsq.test_sub_question_id AND trd.test_result_id = :testResultId
				WHERE tq.test_id = :testId
        ORDER BY tc.display_order 
        ",  [":testResultId" => $id, 'testId' => $testResult->test_id]);

        $analyticList = [];
        $collectionByParentCategoryName = collect($analyticRaw)->groupBy('parent_category_name')->all();
        foreach ($collectionByParentCategoryName as  $byCatItem) {
            $analyticItem = [];
            foreach ($bySubCategoryId = $byCatItem->groupBy('test_question_id')->all() as $index => $item) {
                $numSubQuestion = $item->count();
                $examScore = $item->sum('exam_score');
                $score = $item->sum('score');
                $topScr = TestTopScore::where([
                    'test_id' => $testResult->test_id,
                    'test_category_id' => $item[0]->test_category_id,
                    'test_parrent_name' => $item[0]->parent_category_name])->first();
                $analyticItem[] = [
                    'parent_category_name' => $item[0]->parent_category_name,
                    'category_name' => $item[0]->category_name,
                    'navigation' => $item[0]->navigation,
                    'num_sub_question' => $numSubQuestion,
                    'score' => $score,
                    'exam_score' => $examScore,
                    'top_score_avg' => empty($topScr) ? 0 : $topScr->top_score_avg
                ];
            }
            $analyticList[] = $analyticItem;
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
        if ($testComment->comment_end_time != null)
            return response()->json([
                'status' => 'BAD_REQUEST',
                'message' => '他の方が評価を実施した為、保存できません。'
            ], StatusCode::BAD_REQUEST);
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
