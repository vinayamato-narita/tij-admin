<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Models\LessonCancelHistory;
use Illuminate\Http\Request;

class LessonCancelHistoryController extends BaseController
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
            ['name' => 'lesson_cancel_history_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = LessonCancelHistory::with(['teacher', 'student']);

       if (!empty($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->whereHas('teacher', function ($query) use ($request) {
                    $query->where($this->escapeLikeSentence('teacher_name', $request['search_input']));
                })->orWhere('student_id', $request['search_input']);
            });

            $queryBuilder = $queryBuilder->orWhere(function ($query) use ($request) {
                $query->whereHas('student', function ($query) use ($request) {
                    $query->where($this->escapeLikeSentence('student_name', $request['search_input']));
                });
            });
        }

        if (!empty($request['course_id'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('course_id', $request['course_id']);
            });
        }

        /*        if (!empty($request['course_name'])) {
                    $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                        $query->where($this->escapeLikeSentence('course_name', $request['course_name']));
                    });
                }

                if (!empty($request['campaign_code'])) {
                    $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                        $query->where($this->escapeLikeSentence('campaign_code', $request['campaign_code']));
                    });
                }

                if (!empty($request['paypal_item_number'])) {
                    $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                        $query->where('paypal_item_number', $request['paypal_item_number']);
                    });
                }

                if (!empty($request['is_show']) && $request['is_show'] != 2) {
                    $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                        $query->where('is_show', $request['is_show']);
                    });
                }*/

        $historyList = $queryBuilder->sortable(['cancel_date' => 'desc'])->paginate($pageLimit);

        return view('lessonCancelHistory.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'historyList' => $historyList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
