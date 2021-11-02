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

       if (!empty($request['cancelDateStart']) || !empty($request['cancelDateEnd'])) {
           if (empty($request['cancelDateStart']))
               $queryBuilder->whereDate('cancel_date', '<=', $request['cancelDateEnd']);
           if (empty($request['cancelDateEnd']))
               $queryBuilder->whereDate('cancel_date', '>=', $request['cancelDateStart']);
           if (!empty($request['cancelDateStart']) &&  !empty($request['cancelDateEnd']))
               $queryBuilder->whereBetween('cancel_date', [$request['cancelDateStart'], $request['cancelDateEnd']]);
       }

        if (!empty($request['lessonDateStart']) || !empty($request['lessonDateEnd'])) {
            if (empty($request['lessonDateStart']))
                $queryBuilder->whereDate('lesson_date', '<=', $request['lessonDateEnd']);
            if (empty($request['lessonDateEnd']))
                $queryBuilder->whereDate('lesson_date', '>=', $request['lessonDateStart']);
            if (!empty($request['lessonDateStart']) &&  !empty($request['lessonDateEnd']))
                $queryBuilder->whereBetween('lesson_date', [$request['lessonDateStart'], $request['lessonDateEnd']]);
        }

        if (!empty($request['teacherName'])) {
            $queryBuilder->whereHas('teacher', function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('teacher_name', $request['teacherName']));
            });
        }

        if (!empty($request['studentId'])) {
            $queryBuilder->where('student_id', $request['studentId']);
        }

        if (!empty($request['studentName'])) {
            $queryBuilder->whereHas('student', function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student_name', $request['studentName']));
            });
        }


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
