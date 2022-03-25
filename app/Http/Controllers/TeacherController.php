<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Http\Requests\CreateTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Lesson;
use App\Models\LessonTextLesson;
use App\Models\Teacher;
use App\Models\TeacherLesson;
use App\Models\TimeZone;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Exports\TeacherExport;
use App\Services\CommonService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\LessonHistory;
use App\Exports\TeacherLessonHistoryExport;
use App\Models\TeacherInfo;
use App\Enums\LangType;
use App\Http\Requests\TeacherLangRequest;
use Log;

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
        Session::put('sessionTeacherList', collect($request));

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
                $teacher->teacher_birthday = $request->teacherBirthday == 'null' ? null :  date("Y-m-d",strtotime($request->teacherBirthday));
                $teacher->teacher_university = $request->teacherUniversity ?? "";
                $teacher->teacher_department = $request->teacherDepartment ?? '';
                $teacher->teacher_hobby = $request->teacherHobby ?? '';
                $teacher->teacher_introduction = $request->teacherIntroduction ?? "";
                $teacher->introduce_from_admin = $request->introduceFromAdmin ?? "";
                $teacher->teacher_note = $request->teacherNote ?? "";
                $teacher->password = Hash::make(Str::random(8));
                $teacher->photo_savepath = $request->photoSavepath ?? "";
                $teacher->zoom_personal_meeting_id = $request->zoomPersonalMeetingId;
                $teacher->zoom_password = $request->zoomPassword ?? "";

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

        $teacher = Teacher::where('teacher_id', $id)->with(['timeZone', 'lesson'])->first();
        if (!$teacher) return redirect()->route('teacher.index');

        $teacherEnInfo = TeacherInfo::where(['teacher_id' => $id, 'lang_type' => LangType::EN])->first();
        $teacherZhInfo = TeacherInfo::where(['teacher_id' => $id, 'lang_type' => LangType::ZH])->first();

        return view('teacher.show', [
            'breadcrumbs' => $breadcrumbs,
            'teacher' => $teacher,
            'teacherEnInfo' => $teacherEnInfo,
            'teacherZhInfo' => $teacherZhInfo,
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

        $dataList = $queryBuilder->whereNotIn('lesson_id', $lessonHasAdded)->sortable(['display_order' => 'asc', 'lesson_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'dataList' => $dataList
        ], StatusCode::OK);

    }

    public function registerLesson(Request $request, $id) {
        DB::beginTransaction();
        try {
            foreach ($request->all() as $rq) {
                $tc = new TeacherLesson();
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

        $teacher = Teacher::where('teacher_id', $id)->first();
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
            $teacher = Teacher::where('teacher_id', $id)->first();
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
                $teacher->teacher_birthday = $request->teacherBirthday == 'null' ? null :  date("Y-m-d",strtotime($request->teacherBirthday));
                $teacher->teacher_university = $request->teacherUniversity ?? "";
                $teacher->teacher_department = $request->teacherDepartment ?? '';
                $teacher->teacher_hobby = $request->teacherHobby ?? '';
                $teacher->teacher_introduction = $request->teacherIntroduction ?? "";
                $teacher->introduce_from_admin = $request->introduceFromAdmin ?? "";
                $teacher->teacher_note = $request->teacherNote ?? "";
                $teacher->photo_savepath = $request->photoSavepath ?? "";
                $teacher->zoom_personal_meeting_id = $request->zoomPersonalMeetingId;
                $teacher->zoom_password = $request->zoomPassword ?? "";


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
            $teacher = Teacher::where('teacher_id', $id)->delete();

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

    public function exportTeacher()
    {
        $request = Session::get('sessionTeacherList');
        $fileName = "teacherlist_".date("Y-m-d").".csv";

        return Excel::download(new TeacherExport($request), $fileName);
    }

    public function lessonHistory(Request $request, $id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'teacher_list'],
            ['name' => 'teacher_lesson_history', $id]
        ]);
        $pageLimit = $this->newListLimit($request);

        $teacher = Teacher::where('teacher_id', $id)->firstOrFail();

        Session::put('teacherLessonHistory', collect($request));

        $queryBuilder = LessonHistory::select('lesson_schedule.lesson_date', 
            'lesson_schedule.lesson_starttime',
            'lesson_schedule.lesson_endtime',
            'course.course_name',
            'lesson.lesson_name',
            'lesson_text.lesson_text_name',
            'lesson_history.student_id',
            'student.student_name',
            'lesson_history.lesson_history_id'
        )
        ->join('lesson_schedule', function($join) use ($id) {
            $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id')
            ->where('lesson_schedule.teacher_id', $id);
        })
        ->leftJoin('lesson', function($join) {
            $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
        })
        ->leftJoin('lesson_text', function($join) {
            $join->on('lesson_schedule.lesson_text_id', '=', 'lesson_text.lesson_text_id');
        })
        ->leftJoin('student', function($join) {
            $join->on('lesson_history.student_id', '=', 'student.student_id');
        })
        ->leftJoin('course', function($join) {
            $join->on('lesson_history.course_id', '=', 'course.course_id');
        })
        ->where('lesson_history.student_lesson_reserve_type', '!=', 2);

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course.course_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lesson.lesson_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lesson_text.lesson_text_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_name', $request['search_input']));
            });
        }
       
        if (isset($request['lesson_date_start']) && $request['lesson_date_start'] != null) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('lesson_schedule.lesson_date', '>=', $request['lesson_date_start']);
            });
        }
        if (isset($request['lesson_date_end']) && $request['lesson_date_end'] != null) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('lesson_schedule.lesson_date', '<=', $request['lesson_date_end']);
            });
        }

        if (isset($request['sort'])) {
            if ($request['sort'] == "lesson_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_date','ASC') : $queryBuilder->orderBy('lesson_date','DESC');
            }
            if ($request['sort'] == "lesson_starttime") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_starttime','ASC') : $queryBuilder->orderBy('lesson_starttime','DESC');
            }
            if ($request['sort'] == "course_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('course_name','ASC') : $queryBuilder->orderBy('course_name','DESC');
            }
            if ($request['sort'] == "lesson_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_name','ASC') : $queryBuilder->orderBy('lesson_name','DESC');
            }
            if ($request['sort'] == "lesson_text_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_text_name','ASC') : $queryBuilder->orderBy('lesson_text_name','DESC');
            }
            if ($request['sort'] == "student_id") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_id','ASC') : $queryBuilder->orderBy('student_id','DESC');
            }
            if ($request['sort'] == "student_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_name','ASC') : $queryBuilder->orderBy('student_name','DESC');
            }
        }else {
            $queryBuilder = $queryBuilder->orderBy('lesson_date','DESC');
        }

        $lessonHistories = $queryBuilder->paginate($pageLimit);

        return view('teacher.lesson-history', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'teacher' => $teacher,
            'lessonHistories' => $lessonHistories,
        ]);
    }

    public function lessonHistoryExport($id)
    {
        $request = Session::get('teacherLessonHistory');
        $fileName = "teacher_lesson_history_".date("Y-m-d").".csv";

        return Excel::download(new TeacherLessonHistoryExport($id, $request), $fileName);
    }

    public function lessonHistoryDetail($id)
    {
        $lesson = LessonHistory::select('lesson_schedule.lesson_date', 
            'lesson_schedule.lesson_starttime',
            'lesson_schedule.lesson_endtime',
            'course.course_name',
            'lesson.lesson_name',
            'lesson_text.lesson_text_name',
            'lesson_history.student_id',
            'student.student_name',
            'lesson_history.lesson_history_id',
            'teacher.teacher_id',
            'teacher.teacher_name',
            'lesson_history.teacher_rating',
            'lesson_history.teacher_attitude',
            'lesson_history.teacher_punctual',
            'lesson_history.skype_voice_rating_from_student',
            'lesson_history.comment_from_student_to_office',
            'lesson_history.comment_from_teacher_to_student',
            'lesson_history.comment_from_admin_to_student'
        )
        ->join('lesson_schedule', function($join) {
            $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id');
        })
        ->leftJoin('lesson', function($join) {
            $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
        })
        ->leftJoin('lesson_text', function($join) {
            $join->on('lesson_schedule.lesson_text_id', '=', 'lesson_text.lesson_text_id');
        })
        ->leftJoin('teacher', function($join) {
            $join->on('lesson_schedule.teacher_id', '=', 'teacher.teacher_id');
        })
        ->leftJoin('student', function($join) {
            $join->on('lesson_history.student_id', '=', 'student.student_id');
        })
        ->leftJoin('course', function($join) {
            $join->on('lesson_history.course_id', '=', 'course.course_id');
        })
        ->where('lesson_history.lesson_history_id', $id)->firstOrFail();

        $teacherId = $lesson->teacher_id;

        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'teacher_list'],
            ['name' => 'teacher_lesson_history', $teacherId],
            ['name' => 'teacher_lesson_history_detail', $id],
        ]);

        return view('teacher.lesson-history-detail', [
            'breadcrumbs' => $breadcrumbs,
            'lesson' => $lesson,
            'teacherId' => $teacherId
        ]);
    }

    public function editLang($id, $langType)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'teacher_list'],
            ['name' => 'teacher_show', $id],
            ['name' => 'edit_lang_teacher', $id, $langType],
        ]);

        $teacherInfo = Teacher::where('teacher_id', $id)->firstOrFail();
        
        $teacherLangInfo = TeacherInfo::where(['teacher_id' => $id, 'lang_type' => $langType])->first();
        $teacherInfo->_token = csrf_token();
        $teacherInfo->teacher_name_lang = $teacherLangInfo->teacher_name ?? "";
        $teacherInfo->teacher_nickname_lang = $teacherLangInfo->teacher_nickname ?? "";
        $teacherInfo->teacher_university_lang = $teacherLangInfo->teacher_university ?? "";
        $teacherInfo->teacher_department_lang = $teacherLangInfo->teacher_department ?? "";
        $teacherInfo->teacher_introduction_lang = $teacherLangInfo->teacher_introduction ?? "";
        $teacherInfo->introduce_from_admin_lang = $teacherLangInfo->introduce_from_admin ?? "";

        $teacherInfo->lang_type = $langType;
        $teacherInfo->title = $langType == LangType::EN ? '英語版' : '中国語版';

        return view('teacher.edit-lang', [
            'breadcrumbs' => $breadcrumbs,
            'teacherInfo' => $teacherInfo,
        ]);
    }

    public function updateLang(TeacherLangRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);  
        }
        $teacherInfo = Teacher::where('teacher_id', $request->teacher_id)->first();
        if ($teacherInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $teacherLangInfo = TeacherInfo::updateOrCreate(
            ['teacher_id' => $request->teacher_id, 'lang_type' => $request->lang_type],
            [
                'teacher_name' => $request->teacher_name_lang, 
                'teacher_nickname' => $request->teacher_nickname_lang,
                'teacher_university' => $request->teacher_university_lang,
                'teacher_department' => $request->teacher_department_lang,
                'teacher_introduction' => $request->teacher_introduction_lang,
                'introduce_from_admin' => $request->introduce_from_admin_lang
            ]
        );

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function updatePassword(Request $request)
    {
        if(!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);          
        }
        
        $teacherInfo = Teacher::where('teacher_id', $request->id)->first();
       
        if ($teacherInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $teacherInfo->password = Hash::make($request->password);
        $teacherInfo->save();  
        
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }
}
