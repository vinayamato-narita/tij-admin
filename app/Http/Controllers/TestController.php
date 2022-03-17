<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Components\TIJAdminAzureComponent;
use App\Enums\AzureFolderEnum;
use App\Enums\FileTypeEnum;
use App\Enums\StatusCode;
use App\Enums\TagEnum;
use App\Http\Requests\AddQuestionRequest;
use App\Models\File;
use App\Models\Tag;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\TestQuestion;
use App\Models\TestResult;
use App\Models\TestSubQuestion;
use App\Models\TestSubQuestionCategory;
use App\Models\TestSubQuestionTag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TestRequest;
use App\Enums\TestType;
use Illuminate\Support\Facades\Validator;
use Log;

class TestController extends BaseController
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
            ['name' => 'test_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Test();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('test_name', $request['search_input']))
                    ->orWhere('test_id', $request['search_input'])
                    ->orWhere($this->escapeLikeSentence('test_description', $request['search_input']));
            });
        }

        $testList = $queryBuilder->sortable(['test_id' => 'asc'])->paginate($pageLimit);

        return view('test.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'testList' => $testList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'test_list'],
            ['name' => 'create_test']
        ]);
        $testTypes = TestType::asSelectArray();

        return view('test.create', [
            'breadcrumbs' => $breadcrumbs,
            'testTypes' => $testTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestRequest $request)
    {
        if (!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);
        }

        $test = new Test;

        $test->test_type = $request->test_type;
        $test->test_name = $request->test_name;
        $test->test_description = $request->test_description;
        $test->execution_time = $request->execution_time;
        $test->expire_count = $request->expire_count;
        $test->passing_score = $request->passing_score;
        $test->total_score = $request->total_score ?? 0;
        $test->save();

        return response()->json([
            'status' => 'OK',
            'id' => $test->test_id,
        ], StatusCode::OK);
    }

    public function edit($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'test_list'],
            ['name' => 'show_test', $id],
            ['name' => 'edit_test', $id],
        ]);
        $testInfo = Test::where('test_id', $id)->firstOrFail();
        $testInfo->_token = csrf_token();
        $testTypes = TestType::asSelectArray();

        return view('test.edit', [
            'breadcrumbs' => $breadcrumbs,
            'testInfo' => $testInfo,
            'testTypes' => $testTypes,
        ]);
    }

    public function update(TestRequest $request, $id)
    {
        if (!$request->isMethod('PUT')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);
        }
        $testInfo = Test::where('test_id', $request->test_id)->first();
        if ($testInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }

        $testInfo = Test::where('test_id', $id)->firstOrFail();
        $testInfo->test_type = $request->test_type;
        $testInfo->test_name = $request->test_name;
        $testInfo->test_description = $request->test_description;
        $testInfo->execution_time = $request->execution_time;
        $testInfo->expire_count = $request->expire_count;
        $testInfo->passing_score = $request->passing_score;
        $testInfo->total_score = $request->total_score;

        $testInfo->save();

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function destroy($id)
    {
        try {
            $test = Test::where('test_id', $id)->delete();

        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' テストが削除されました',
            'data' => [],
        ], StatusCode::OK);
    }

    public function show($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'test_list'],
            ['name' => 'test_show', $id]
        ]);

        $test = Test::with('testQuestions.testSubQuestions', 'courses', 'lessons')->where('test_id', $id)->first();
        if (!$test) return redirect()->route('test.index');
        $isHasTestResult = TestResult::where('test_id', $id)->exists();
        return view('test.show', [
            'breadcrumbs' => $breadcrumbs,
            'test' => $test,
            'isHasTestResult' => $isHasTestResult
        ]);
    }

    public function addQuestion($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'test_list'],
            ['name' => 'test_show', $id],
            ['name' => 'test_add_question', $id],

        ]);

        $test = Test::where('test_id', $id)->first();
        if (!$test) return redirect()->route('test.index');
        $tags = Tag::all();
        $testCategories = TestCategory::all()->sortBy('display_order')->toArray();
        return view('test.addQuestion', [
            'breadcrumbs' => $breadcrumbs,
            'test' => $test,
            'tags' => $tags,
            'testCategories' => $testCategories
        ]);

    }

    public function checkNavigation($id, Request $request)
    {
        if (empty($request->navigation))
            return response()->json([
                'valid' => false,
            ], StatusCode::OK);
        $valid = !TestQuestion::where(function ($query) use ($request, $id) {
            if (isset($request['test_question_id'])) {
                $query->where('test_question_id', '!=', $request["test_question_id"]);
            }
            $query->where(['navigation' => $request["navigation"], 'test_id' => $id]);
        })->exists();
        return response()->json([
            'valid' => $valid,
        ], StatusCode::OK);
    }


    public function addTag(Request $request, $id)
    {
        if (!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);
        }


        $validator = Validator::make($request->all(), [
            'tagName' => 'required|max:255',

        ]);
        if ($validator->fails())
            return response()->json([
                'status' => 'UNPROCESSABLE_ENTITY',
            ], StatusCode::UNPROCESSABLE_ENTITY);
        try {
            $tag = new Tag();
            $tag->tag_name = $request->tagName;
            $tag->css_class = '';
            $tag->tag_category = TagEnum::TAG_TEST;
            $tag->save();
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }
        return response()->json([
            'status' => 'OK',
            'tag_id' => $tag->tag_id
        ], StatusCode::OK);
    }

    public function addQuestionPost(AddQuestionRequest $request, $id)
    {
        if (!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);
        }

        $test = Test::find($id);
        if (!$test)
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        $totalScore = $test->total_score;

        DB::beginTransaction();

        try {
            $testQuestion = new TestQuestion();
            $testQuestion->navigation = $request->navigation ?? '';
            $displayOrder = TestQuestion::where('test_id', $id)->max('display_order') + 1;
            $testQuestion->display_order = $displayOrder;
            $testQuestion->question_content = $request->questionContent ?? '';

            if (isset($request->fileSelected)) {
                $name = TIJAdminAzureComponent::upload(AzureFolderEnum::TEST, $request->fileSelected);
                if ($name) {
                    $file = new File();
                    $file->file_name = $name;
                    $file->file_name_original = $request->fileSelected->getClientOriginalName();
                    $file->file_path = AzureFolderEnum::TEST . '/' . $name;
                    $file->file_type = FileTypeEnum::TEST_RELATED;
                    $file->save();
                    $testQuestion->file_id = $file->file_id;
                }
            }
            if (isset($request->fileId)) {
                $storedFile = File::query()->find($request->fileId);
                if ($storedFile)
                    $testQuestion->file_id = $storedFile->file_id;

            }
            $testQuestion->test_id = $test->test_id;
            $testQuestion->save();

            $subQuestions = json_decode($request->subQuestion);

            $files = $request->allFiles();
            $convertFiles = [];
            foreach ($files as $indexFile => $f) {
                if (strpos($indexFile,'pushedQuestionFile') !== false)
                    $convertFiles[str_replace('pushedQuestionFile_', '', $indexFile)] = $f;
            }

            foreach ($subQuestions as $index => $subQuestion) {
                $testSubQuestion = new TestSubQuestion();
                $testSubQuestion->test_question_id = $testQuestion->test_question_id;
                $testSubQuestion->display_order = ++$index;
                $testSubQuestion->sub_question_content = $subQuestion->question;
                $testSubQuestion->answer1 = $subQuestion->answer1;
                $testSubQuestion->answer2 = $subQuestion->answer2;
                $testSubQuestion->answer3 = $subQuestion->answer3;
                $testSubQuestion->answer4 = $subQuestion->answer4;
                $testSubQuestion->explanation = $subQuestion->explanation;
                if (!empty($subQuestion->fileId)) {
                    $storedFile = File::query()->find($request->fileId);
                    if ($storedFile)
                        $testSubQuestion->explanation_file_id = $storedFile->file_id;
                }
                if (!empty($convertFiles[$index])) {
                    $name = TIJAdminAzureComponent::upload(AzureFolderEnum::TEST, $convertFiles[$index]);
                    if ($name) {
                        $file = new File();
                        $file->file_name = $name;
                        $file->file_name_original = $f->getClientOriginalName();
                        $file->file_path = AzureFolderEnum::TEST . '/' . $name;
                        $file->file_type = FileTypeEnum::TEST_RELATED;
                        $file->save();
                        $testSubQuestion->explanation_file_id = $file->file_id;
                    }

                }
                $testSubQuestion->score = $subQuestion->score;
                $totalScore += $subQuestion->score;

                $testSubQuestion->save();

                if (isset($subQuestion->testCategory)) {
                    $testSubQuestionCategory = new TestSubQuestionCategory();
                    $testSubQuestionCategory->test_sub_question_id = $testSubQuestion->test_sub_question_id;
                    $testSubQuestionCategory->test_category_id = $subQuestion->testCategory;
                    $testSubQuestionCategory->save();
                }


                if (!empty($subQuestion->value)) {
                    foreach ($subQuestion->value as $tag) {
                        $tsqsTag = new TestSubQuestionTag();
                        $tsqsTag->test_sub_question_id = $testSubQuestion->test_sub_question_id;
                        $tsqsTag->tag_id = $tag->id;
                        $tsqsTag->save();
                    }
                }

            }
            $test->total_score = $totalScore;
            $test->save();


            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'status' => 'NG',
                'exception' => $exception->getMessage(),
            ], StatusCode::INTERNAL_ERR);
        }


        return response()->json([
            'status' => 'OK',
            'id' => $test->test_id,
        ], StatusCode::OK);

    }

    public function editQuestion($id, $testQuestionId)
    {

        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'test_list'],
            ['name' => 'test_show', $id],
            ['name' => 'test_edit_question', $id, $testQuestionId],

        ]);

        $test = Test::where('test_id', $id)->first();
        $testQuestion = TestQuestion::with(['testSubQuestions.testCategory', 'file', 'testSubQuestions.file', 'testSubQuestions.tags'])->where('test_question_id', $testQuestionId)->first();
        if (!$test || !$testQuestion) return redirect()->route('test.index');

        $tags = Tag::all();
        $testCategories = TestCategory::all()->sortBy('display_order')->toArray();
        $isHasTestResult = TestResult::where('test_id', $id)->exists();

        return view('test.editQuestion', [
            'breadcrumbs' => $breadcrumbs,
            'test' => $test,
            'testQuestion' => $testQuestion,
            'tags' => $tags,
            'testCategories' => $testCategories,
            'isHasTestResult' => $isHasTestResult
        ]);

    }

    public function QuestionUpdate(AddQuestionRequest $request, $id, $testQuestionId)
    {
        if (!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);
        }

        $test = Test::find($id);
        $testQuestion = TestQuestion::find($testQuestionId);

        if (!$test || !$testQuestion)
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        $totalScore = $test->total_score - $testQuestion->total_score;

        DB::beginTransaction();

        try {
            $testQuestion->navigation = $request->navigation ?? '';
            $testQuestion->question_content = $request->questionContent ?? '';

            if (isset($request->fileSelected)) {
                $name = TIJAdminAzureComponent::upload(AzureFolderEnum::TEST, $request->fileSelected);
                if ($name) {
                    $file = new File();
                    $file->file_name = $name;
                    $file->file_name_original = $request->fileSelected->getClientOriginalName();
                    $file->file_path = AzureFolderEnum::TEST . '/' . $name;
                    $file->file_type = FileTypeEnum::TEST_RELATED;
                    $file->save();
                    $testQuestion->file_id = $file->file_id;
                }
            }
            if (isset($request->fileId)) {
                $storedFile = File::query()->find($request->fileId);
                if ($storedFile)
                    $testQuestion->file_id = $storedFile->file_id;

            }
            $testQuestion->test_id = $test->test_id;
            $testQuestion->save();

            $subQuestions = json_decode($request->subQuestion);

            $files = $request->allFiles();
            $convertFiles = [];
            foreach ($files as $indexFile => $f) {
                if (strpos($indexFile,'pushedQuestionFile') !== false)
                    $convertFiles[str_replace('pushedQuestionFile_', '', $indexFile)] = $f;
            }

            //get subquestion has been removed
            $subQuestionToRemoveIds = collect(TestSubQuestion::where('test_question_id', $testQuestionId)->get()->pluck('test_sub_question_id'))->diff(array_column($subQuestions, 'testSubQuestionId'));
            if (!empty($subQuestionToRemoveIds->toArray()) && TestResult::where('test_id', $id)->exists())
                return response()->json([
                    'status' => 'NG',
                    'msg' => 'cannot remove subquestion has test result',
                ], StatusCode::UNPROCESSABLE_ENTITY);
            else {
                TestSubQuestion::whereIn('test_sub_question_id', $subQuestionToRemoveIds)->delete();
            }

            foreach ($subQuestions as $index => $subQuestion) {
                if (!empty($subQuestion->testSubQuestionId))
                {
                    $testSubQuestion = TestSubQuestion::with('tags')->find($subQuestion->testSubQuestionId);

                }
                else {
                    $testSubQuestion = new TestSubQuestion();

                }
                $testSubQuestion->test_question_id = $testQuestion->test_question_id;
                $testSubQuestion->display_order = ++$index;
                $testSubQuestion->sub_question_content = $subQuestion->question;
                $testSubQuestion->answer1 = $subQuestion->answer1;
                $testSubQuestion->answer2 = $subQuestion->answer2;
                $testSubQuestion->answer3 = $subQuestion->answer3;
                $testSubQuestion->answer4 = $subQuestion->answer4;
                $testSubQuestion->explanation = $subQuestion->explanation;
                if (!empty($subQuestion->fileId)) {
                    $storedFile = File::query()->find($request->fileId);
                    if ($storedFile)
                        $testSubQuestion->explanation_file_id = $storedFile->file_id;
                }
                if (!empty($convertFiles[$index])) {
                    $name = TIJAdminAzureComponent::upload(AzureFolderEnum::TEST , $convertFiles[$index]);
                    if ($name) {
                        $file = new File();
                        $file->file_name = $name;
                        $file->file_name_original = $f->getClientOriginalName();
                        $file->file_path = AzureFolderEnum::TEST . '/' . $name;
                        $file->file_type = FileTypeEnum::TEST_RELATED;
                        $file->save();
                        $testSubQuestion->explanation_file_id = $file->file_id;
                    }

                }
                $testSubQuestion->score = $subQuestion->score;
                $totalScore += $subQuestion->score;

                $testSubQuestion->save();
                if (isset($subQuestion->testCategory)) {
                    $oldSubQuestionCategory = TestSubQuestionCategory::where('test_sub_question_id', $testSubQuestion->test_sub_question_id)->first();
                    if ($oldSubQuestionCategory) {
                        $oldSubQuestionCategory->test_sub_question_id = $testSubQuestion->test_sub_question_id;
                        $oldSubQuestionCategory->test_category_id = $subQuestion->testCategory;
                        $oldSubQuestionCategory->save();
                    } else {
                        $testSubQuestionCategory = new TestSubQuestionCategory();
                        $testSubQuestionCategory->test_sub_question_id = $testSubQuestion->test_sub_question_id;
                        $testSubQuestionCategory->test_category_id = $subQuestion->testCategory;
                        $testSubQuestionCategory->save();
                    }

                }
                $savedTag = $testSubQuestion->tags->pluck('tag_id');


                $pushedTagIds = collect(array_column($subQuestion->value, 'id'));

                //get tag be removed in UI
                $tagToRemove = $savedTag->diff($pushedTagIds);
                TestSubQuestionTag::whereIn('tag_id', $tagToRemove)->delete();

                //get tag be added in UI
                $tagToAdd = $pushedTagIds->diff($savedTag);

                foreach ($tagToAdd->all() as $tag) {
                    $tsqsTag = new TestSubQuestionTag();
                    $tsqsTag->test_sub_question_id = $testSubQuestion->test_sub_question_id;
                    $tsqsTag->tag_id = $tag;
                    $tsqsTag->save();
                }

            }


            $test->total_score = $totalScore;
            $test->save();
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'status' => 'NG',
                'exception' => $exception->getMessage(),
            ], StatusCode::INTERNAL_ERR);
        }


        return response()->json([
            'status' => 'OK',
            'id' => $test->test_id,
        ], StatusCode::OK);

    }


    public function deleteQuestion($id, $testQuestionId)
    {
        if (TestResult::where('test_id', $id)->exists()) {
            return response()->json([
                'status' => 'NOT_FOUND',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }

        try {
            $testQuestion = TestQuestion::where('test_question_id', $testQuestionId)->delete();


        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::INTERNAL_ERR);
        }
        return response()->json([
            'status' => 'OK',
            'message' => '大問が削除されました',
            'data' => [],
        ], StatusCode::OK);
    }

    public function listQuestionAttach ($id)
    {
        $test = Test::with('testQuestions.testSubQuestions')->where('test_id', $id)->first();
        if (!$test)
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);

        return response()->json([
            'status' => 'OK',
            'dataList' => $test->testQuestions->toArray(),
        ], StatusCode::OK);


    }

    public function listQuestionAttachUpdate (Request $request, $id)
    {
        $test = Test::where('test_id', $id)->first();

        if (empty($request->testQuestions) || !$test)
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);

        DB::beginTransaction();
        try {
            $testQuestions = json_decode($request->testQuestions);
            $totalScore =0 ;
            foreach ($testQuestions as  $index => $testQuestion) {
                $testQuestionDb = TestQuestion::find($testQuestion->test_question_id);
                $orderValue = ++$index;
                $testQuestionDb->display_order = $orderValue;
                $testQuestionDb->save();
                foreach ($testQuestion->test_sub_questions as $indexTSQuestion => $testSubQuestion) {
                    $testSubQuestionDb = TestSubQuestion::find($testSubQuestion->test_sub_question_id);
                    $orderValue = ++$indexTSQuestion;
                    $testSubQuestionDb->display_order = $orderValue;
                    $testSubQuestionDb->score = $testSubQuestion->score;
                    $testSubQuestionDb->save();
                    $totalScore += $testSubQuestion->score;

                }

            }

            $test->total_score = $totalScore;
            $test->save();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::INTERNAL_ERR);
        }


        return response()->json([
            'status' => 'OK',
            'data' => [],
        ], StatusCode::OK);

    }

}
