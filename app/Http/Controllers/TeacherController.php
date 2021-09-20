<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Http\Requests\CreateTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Lesson;
use App\Models\Lesson_Text_Lesson;
use App\Models\Teacher;
use App\Models\TeacherLesson;
use App\Models\TimeZone;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends BaseController
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
            ['name' => 'teacher_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Teacher();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('teacher_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher_email', $request['search_input']));
            });
        }

        $teacherList = $queryBuilder->sortable(['display_order' => 'asc'])->paginate($pageLimit);

        return view('teacher.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'teacherList' => $teacherList,
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
            ['name' => 'teacher_list'],
            ['name' => 'teacher_add']
        ]);

        $timeZones = TimeZone::all()->toArray();
        return view('teacher.add', [
            'breadcrumbs' => $breadcrumbs,
            'timeZones' => $timeZones
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeacherRequest $request)
    {
        if($request->isMethod('POST')){
            DB::beginTransaction();
            try {
                $teacher = new Teacher();
                $teacher->display_order = $request->displayOrder;
                $teacher->teacher_nickname = $request->nickName;
                $teacher->teacher_name = $request->teacherName;
                $teacher->teacher_email = $request->mail;
                $teacher->timezone_id = $request->timeZone;
                $teacher->is_free_teacher = $request->isFreeTeacher;
                $teacher->teacher_sex = $request->teacherSex;
                $teacher->teacher_birthday = $request->teacherBirthday == 'null' ? null : $request->teacherBirthday;
                $teacher->teacher_university = $request->teacherUniversity　?? '';
                $teacher->teacher_department = $request->teacherDepartment ?? '';
                $teacher->teacher_hobby = $request->teacherHobby ?? '';
                $teacher->teacher_introduction = $request->teacherIntroduction ?? "";
                $teacher->introduce_from_admin = $request->introduceFromAdmin;
                $teacher->teacher_note = $request->teacherNote ?? "";
                $teacher->teacher_password = '';
                $teacher->photo_savepath = $request->photoSavepath ?? "";

                $teacher->save();
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
            ['name' => 'teacher_list'],
            ['name' => 'teacher_show', $id]
        ]);

        $teacher = Teacher::where('id', $id)->with(['timeZone', 'lesson'])->first();
        if (!$teacher) return redirect()->route('teacher.index');
        return view('teacher.show', [
            'breadcrumbs' => $breadcrumbs,
            'teacher' => $teacher
        ]);
    }

    public function lesson(Request $request, $id)
    {
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Lesson();

        if (isset($request['inputSearch'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('lesson_name', $request['inputSearch']));
            });
        }
        $lessonHasAdded = TeacherLesson::where('teacher_id', $id)->pluck('lesson_id');

        $lessonList = $queryBuilder->whereNotIn('lesson_id', $lessonHasAdded)->sortable(['display_order' => 'asc', 'lesson_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'lessonList' => $lessonList
        ], StatusCode::OK);

    }

    public function registerTextLesson(Request $request, $id) {
        DB::beginTransaction();
        try {
            foreach ($request->all() as $rq) {
                $tc = new Lesson_Text_Lesson();
                $tc->teacher_id = $id;
                $tc->lesson_id = $rq;
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

    public function TeacherLessonDelete($id, $lessonId)
    {

        try {
            $teacherLesson = TeacherLesson::where([
                'teacher_id' => $id,
                'lesson_id' => $lessonId
            ])->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' レッスンの解除が完了しました。',
            'data' => [],
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
            ['name' => 'teacher_list'],
            ['name' => 'teacher_show', $id],
            ['name' => 'teacher_edit', $id]
        ]);

        $teacher = Teacher::where('id', $id)->first();
        if (!$teacher) return redirect()->route('teacher.index');
        $timeZones = TimeZone::all()->toArray();
        return view('teacher.edit', [
            'breadcrumbs' => $breadcrumbs,
            'timeZones' => $timeZones,
            'teacher' => $teacher
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeacherRequest $request, $id)
    {
        if($request->isMethod('PUT')){
            $teacher = Teacher::where('id', $id)->first();
            if (!$teacher) {
                return response()->json([
                    'status' => 'NOT_FOUND',
                ], StatusCode::NOT_FOUND);
            }

            DB::beginTransaction();
            try {
                $teacher->display_order = $request->displayOrder;
                $teacher->teacher_nickname = $request->nickName;
                $teacher->teacher_name = $request->teacherName;
                $teacher->teacher_email = $request->mail;
                $teacher->timezone_id = $request->timeZone;
                $teacher->is_free_teacher = $request->isFreeTeacher;
                $teacher->teacher_sex = $request->teacherSex;
                $teacher->teacher_birthday = ($request->teacherBirthday == 'null' || $request->teacherBirthday == null) ? null : $request->teacherBirthday;
                $teacher->teacher_university = $request->teacherUniversity　?? '';
                $teacher->teacher_department = $request->teacherDepartment ?? '';
                $teacher->teacher_hobby = $request->teacherHobby ?? '';
                $teacher->teacher_introduction = $request->teacherIntroduction ?? "";
                $teacher->introduce_from_admin = $request->introduceFromAdmin ?? "";
                $teacher->teacher_note = $request->teacherNote ?? "";
                $teacher->teacher_password = '';
                $teacher->photo_savepath = $request->photoSavepath ?? "";


                $teacher->save();
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
            $teacher = Teacher::where('id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' 講師が削除されました',
            'data' => [],
        ], StatusCode::OK);
    }
}
