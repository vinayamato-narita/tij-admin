<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Http\Requests\StoreUpdateLessonRequest;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonController extends BaseController
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
            ['name' => 'lesson_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Lesson();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('lesson_name', $request['search_input']))
                    ->orWhere('lesson_id', $request['search_input'])
                    ->orWhere($this->escapeLikeSentence('lesson_description', $request['search_input']));
            });
        }

        $lessonList = $queryBuilder->sortable(['display_order' => 'asc', 'lesson_name' => 'asc'])->paginate($pageLimit);

        return view('lesson.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'lessonList' => $lessonList,
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
            ['name' => 'lesson_list'],
            ['name' => 'lesson_add']
        ]);

        return view('lesson.add', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateLessonRequest $request)
    {
        if($request->isMethod('POST')){
            DB::beginTransaction();
            try {
                $lesson = new Lesson();
                $lesson->display_order = $request->displayOrder;
                $lesson->lesson_name = $request->lessonName;
                $lesson->lesson_description = $request->lessonDescription ?? '';
                $lesson->is_test_lesson = $request->isTestLesson ==  'true' ? true : false;
                $lesson->is_show_to_search = $request->isShowToSearch ==  'true' ? true : false;;
                $lesson->is_show_to_teacher_detail = $request->isShoToTeacherDetail == 'true' ? true: false;

                $lesson->save();
                DB::commit();
                return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
            } catch (\Exception $exception) {
                DB::rollBack();
                return response()->json([
                    'status' => 'INTERNAL_ERR',
                ], StatusCode::INTERNAL_ERR);
            }

        }
        return response()->json([
            'status' => 'METHOD_NOT_ALLOWED',
        ], StatusCode::METHOD_NOT_ALLOWED);
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
            ['name' => 'lesson_list'],
            ['name' => 'lesson_show', $id]
        ]);

        $lesson = Lesson::where('lesson_id', $id)->first();
        if (!$lesson) return redirect()->route('lesson.index');
        return view('lesson.show', [
            'breadcrumbs' => $breadcrumbs,
            'lesson' => $lesson
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
            ['name' => 'lesson_list'],
            ['name' => 'lesson_show', $id],
            ['name' => 'lesson_edit', $id]
        ]);

        $lesson = Lesson::where('lesson_id', $id)->first();
        if (!$lesson) return redirect()->route('lesson.index');
        return view('lesson.edit', [
            'breadcrumbs' => $breadcrumbs,
            'lesson' => $lesson
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateLessonRequest $request, $id)
    {
        if($request->isMethod('PUT')){
            $lesson = Lesson::where('lesson_id', $id)->first();
            if (!$lesson) {
                return response()->json([
                    'status' => 'NOT_FOUND',
                ], StatusCode::NOT_FOUND);
            }

            DB::beginTransaction();
            try {
                $lesson->display_order = $request->displayOrder;
                $lesson->lesson_name = $request->lessonName;
                $lesson->lesson_description = $request->lessonDescription ?? '';
                $lesson->is_test_lesson = $request->isTestLesson ==  'true' ? true : false;
                $lesson->is_show_to_search = $request->isShowToSearch ==  'true' ? true : false;;
                $lesson->is_show_to_teacher_detail = $request->isShowToSearchDetail == 'true' ? true: false;

                $lesson->save();
                DB::commit();
                return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
            } catch (\Exception $exception) {
                DB::rollBack();
                return response()->json([
                    'status' => 'INTERNAL_ERR',
                ], StatusCode::INTERNAL_ERR);
            }

        }
        return response()->json([
            'status' => 'METHOD_NOT_ALLOWED',
        ], StatusCode::METHOD_NOT_ALLOWED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $lesson = Lesson::where('lesson_id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' レッスンが削除されました',
            'data' => [],
        ], StatusCode::OK);
    }


}
