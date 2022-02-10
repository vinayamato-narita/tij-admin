<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\TestType;
use App\Models\TestResult;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        return view('abilityTestResult.index1', [
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
