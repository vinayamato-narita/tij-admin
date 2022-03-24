<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Enums\TestType;
use App\Http\Requests\LessonLangRequest;
use App\Http\Requests\StoreUpdateLessonRequest;
use App\Models\Lesson;
use App\Models\LessonInfo;
use App\Models\LessonTest;
use App\Models\LessonText;
use App\Models\LessonTextLesson;
use App\Models\Preparation;
use App\Models\PreparationLesson;
use App\Models\Review;
use App\Models\ReviewLesson;
use App\Models\Test;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

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

        $lessonList = $queryBuilder->sortable(['lesson_name' => 'asc'])->paginate($pageLimit);

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
                $lesson->display_order = 1;
                $lesson->lesson_name = $request->lessonName;
                $lesson->lesson_description = $request->lessonDescription ?? '';
                $lesson->lesson_code = $request->lessonCode ?? '';
                // $lesson->is_test_lesson = $request->isTestLesson ==  'true' ? true : false;
                // $lesson->is_show_to_search = $request->isShowToSearch ==  'true' ? true : false;;
                // $lesson->is_show_to_teacher_detail = $request->isShoToTeacherDetail == 'true' ? true: false;

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

        $lesson = Lesson::where('lesson_id', $id)->with('lessonText', 'preparations', 'reviews', 'lesson_infos', 'confirmTest')->first();
        if (!$lesson) return redirect()->route('lesson.index');
        return view('lesson.show', [
            'breadcrumbs' => $breadcrumbs,
            'lesson' => $lesson
        ]);
    }

    public function textLessonDelete($id, $textLessonId)
    {

        try {
            $textLesson = LessonTextLesson::where([
                'lesson_id' => $id,
                'lesson_text_id' => $textLessonId
            ])->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' テキストの解除が完了しました。',
            'data' => [],
        ], StatusCode::OK);
    }

    public function textLesson(Request $request, $id)
    {
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new LessonText();

        if (isset($request['inputSearch'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('lesson_text_name', $request['inputSearch']));
            });
        }
        $lessonTextHasAdded = LessonTextLesson::where('lesson_id', $id)->pluck('lesson_text_id');

        $lessonTextList = $queryBuilder->whereNotIn('lesson_text_id', $lessonTextHasAdded)->sortable(['lesson_text_no' => 'asc', 'lesson_text_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'dataList' => $lessonTextList
        ], StatusCode::OK);

    }

    public function preparation(Request $request)
    {
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Preparation();

        if (isset($request['inputSearch'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('preparation_name', $request['inputSearch']));
            });
        }

        $preparationList = $queryBuilder->sortable(['preparation_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'dataList' => $preparationList
        ], StatusCode::OK);

    }

    public function registerPreparation(Request $request)
    {
        DB::beginTransaction();
        try {
            $pl = new PreparationLesson();
            $pl->preparation_id = $request->preparationId;
            $pl->lesson_id = $request->lessonId;
            $pl->save();
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }

        DB::commit();
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function preparationDelete($id, $preparationId)
    {

        try {
            $preparationLesson = PreparationLesson::where([
                'lesson_id' => $id,
                'preparation_id' => $preparationId
            ])->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' 予習の解除が完了しました。',
            'data' => [],
        ], StatusCode::OK);
    }

    public function review(Request $request)
    {
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Review();

        if (isset($request['inputSearch'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('review_name', $request['inputSearch']));
            });
        }

        $reviewList = $queryBuilder->sortable(['review_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'dataList' => $reviewList
        ], StatusCode::OK);

    }

    public function registerReview(Request $request)
    {
        DB::beginTransaction();
        try {
            $rl = new ReviewLesson();
            $rl->review_id = $request->reviewId;
            $rl->lesson_id = $request->lessonId;
            $rl->save();
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }

        DB::commit();
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function reviewDelete($id, $reviewId)
    {

        try {
            $reviewLesson = ReviewLesson::where([
                'lesson_id' => $id,
                'review_id' => $reviewId
            ])->delete();

        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' 復習の解除が完了しました。',
            'data' => [],
        ], StatusCode::OK);
    }


    public function registerTextLesson(Request $request, $id) {
        DB::beginTransaction();
        try {
            foreach ($request->all() as $rq) {
                $tc = new LessonTextLesson();
                $tc->lesson_text_id = $rq;
                $tc->lesson_id = $id;
                $tc->save();
            }
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }

        DB::commit();
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
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
                $lesson->lesson_name = $request->lessonName;
                $lesson->lesson_description = $request->lessonDescription ?? '';
                $lesson->lesson_code = $request->lessonCode;
                // $lesson->is_test_lesson = $request->isTestLesson ==  'true' ? true : false;
                // $lesson->is_show_to_search = $request->isShowToSearch ==  'true' ? true : false;;
                // $lesson->is_show_to_teacher_detail = $request->isShowToSearchDetail == 'true' ? true: false;

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

    public function editLang($id, $langType)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'lesson_list'],
            ['name' => 'lesson_show', $id],
            ['name' => 'edit_lang_lesson', $id, $langType],
        ]);
        $lesson = Lesson::where('lesson_id', $id)->first();
        if (!$lesson) return redirect()->route('lesson.index');
        $lessonInfo = LessonInfo::where(['lesson_id' => $id, 'lang_type' => $langType])->first();

        return view('lesson.editLang', [
            'breadcrumbs' => $breadcrumbs,
            'lessonInfo' => $lessonInfo,
            'lesson' => $lesson,
            'lang' => $langType
        ]);
    }

    public function updateLang(LessonLangRequest $request)
    {
        if (!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::BAD_REQUEST);
        }

        $lesson = Lesson::where('lesson_id', $request->lesson_id)->first();
        if ($lesson == null) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::NOT_FOUND);
        }
        $lessonLangInfo = LessonInfo::updateOrCreate(
            ['lesson_id' => $request->lesson_id, 'lang_type' => $request->lang],
            ['lesson_name' => $request->lesson_name, 'lesson_description' => $request->lesson_description]
        );
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function confirmTest(Request $request, $id)
    {
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Test();

        if (isset($request['inputSearch'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('test_name', $request['inputSearch']));
            });
        }
        $testHasAdded = LessonTest::where('lesson_id', $id)->pluck('test_id');

        $testList = $queryBuilder->whereNotIn('test_id', $testHasAdded)->where('test_type', TestType::CONFIRMED)->sortable(['test_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'dataList' => $testList
        ], StatusCode::OK);
    }

    public function registerConfirmTest(Request $request, $id) {
        DB::beginTransaction();
        try {
            foreach ($request->all() as $rq) {
                $tc = new LessonTest();
                $tc->test_id = $rq;
                $tc->lesson_id = $id;
                $tc->save();
            }
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }

        DB::commit();
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function confirmTestDelete($id, $testId)
    {

        try {
            $textLesson = LessonTest::where([
                'lesson_id' => $id,
                'test_id' => $testId
            ])->delete();

        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'NOT_FOUND',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => 'テストの解除が完了しました。',
            'data' => [],
        ], StatusCode::OK);
    }


}
