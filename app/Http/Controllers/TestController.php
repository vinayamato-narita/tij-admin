<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Models\Test;
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
}
