<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Components\TIJAdminAzureComponent;
use App\Enums\AzureFolderEnum;
use App\Enums\FileTypeEnum;
use App\Enums\StatusCode;
use App\Http\Requests\AddQuestionRequest;
use App\Models\File;
use App\Models\Test;
use App\Models\TestQuestion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TestRequest;
use App\Enums\TestType;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestRequest $request)
    {
        if(!$request->isMethod('POST')){
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
        if(!$request->isMethod('PUT')){
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

    public function destroy($id) {
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

        $test = Test::where('test_id', $id)->first();
        if (!$test) return redirect()->route('test.index');
        return view('test.show', [
            'breadcrumbs' => $breadcrumbs,
            'test' => $test
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
        return view('test.addQuestion', [
            'breadcrumbs' => $breadcrumbs,
            'test' => $test
        ]);

    }

    public function addQuestionPost(AddQuestionRequest $request, $id)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);
        }

        $test = Test::find($id);
        if (!$test)
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        DB::beginTransaction();

        try {
            $testQuestion = new TestQuestion();
            $testQuestion->navigation = $request->navigation  ?? '';
            $testQuestion->display_order = $request->displayOrder  ?? 0;
            $testQuestion->question_content = $request->questionContent  ?? '';

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
                    $testQuestion->file_id = $file->file_id;

            }
            $testQuestion->save();

            DB::commit();
        }
        catch (\Exception $exception) {
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

}
