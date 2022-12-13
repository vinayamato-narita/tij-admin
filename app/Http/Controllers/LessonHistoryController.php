<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Components\CommonComponent;
use App\Components\DateTimeComponent;
use App\Enums\AdminRole;
use App\Enums\CourseTypeEnum;
use App\Models\LessonHistory;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Response;


class LessonHistoryController extends BaseController
{
    /**
     * Display a listing of the resource.*
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        if (empty($request['lesson_date_start']) && empty($request['lesson_date_end'])) {
            $data['lesson_date_end'] = Carbon::now()->format('Y/m/d');
            $data['lesson_date_start'] = Carbon::now()->startOfMonth()->format('Y/m/d');
            $request->replace($data);
        }


        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'lesson_history']
        ]);
        $pageLimit = $this->newListLimit($request);
        Session::put('lessonHistory', collect($request));
        $queryBuilder = LessonHistory::select(
            'teacher.teacher_id',
            'teacher.teacher_name',
            'lesson_schedule.lesson_date',
            'lesson_schedule.lesson_starttime',
            'lesson_schedule.lesson_endtime',
            'course.course_name',
            'lesson.lesson_name',
            'lesson_text.lesson_text_name',
            'lesson_history.student_id',
            'student.student_name',
            'lesson_history.lesson_history_id'
        )
            ->join('lesson_schedule', function ($join) {
                $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id');
            })
            ->join('teacher', function ($join) {
                $join->on('lesson_schedule.teacher_id', '=', 'teacher.teacher_id');
            })
            ->join('lesson', function ($join) {
                $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
            })
            ->leftJoin('lesson_text_lesson', function($join) {
                $join->on('lesson.lesson_id', '=', 'lesson_text_lesson.lesson_id');
            })
            ->leftJoin('lesson_text', function($join) {
                $join->on('lesson_text_lesson.lesson_text_id', '=', 'lesson_text.lesson_text_id');
            })
            ->join('student', function ($join) {
                $join->on('lesson_history.student_id', '=', 'student.student_id');
            })
            ->leftJoin('course', function ($join) {
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
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_date', 'ASC') : $queryBuilder->orderBy('lesson_date', 'DESC');
            }
            if ($request['sort'] == "lesson_starttime") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_starttime', 'ASC') : $queryBuilder->orderBy('lesson_starttime', 'DESC');
            }
            if ($request['sort'] == "course_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('course_name', 'ASC') : $queryBuilder->orderBy('course_name', 'DESC');
            }
            if ($request['sort'] == "lesson_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_name', 'ASC') : $queryBuilder->orderBy('lesson_name', 'DESC');
            }
            if ($request['sort'] == "lesson_text_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_text_name', 'ASC') : $queryBuilder->orderBy('lesson_text_name', 'DESC');
            }
            if ($request['sort'] == "teacher_id") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('teacher_id', 'ASC') : $queryBuilder->orderBy('teacher_id', 'DESC');
            }
            if ($request['sort'] == "teacher_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('teacher_name', 'ASC') : $queryBuilder->orderBy('teacher_name', 'DESC');
            }
            if ($request['sort'] == "student_id") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_id', 'ASC') : $queryBuilder->orderBy('student_id', 'DESC');
            }
            if ($request['sort'] == "student_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_name', 'ASC') : $queryBuilder->orderBy('student_name', 'DESC');
            }
        } else {
            $queryBuilder = $queryBuilder->orderBy('lesson_date', 'DESC');
        }

        $lessonHistories = $queryBuilder->paginate($pageLimit);
        $adminSystem = Auth::user()->role == AdminRole::SYSTEM;

        return view('lesson_history.list', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'lessonHistories' => $lessonHistories,
            'adminSystem' => $adminSystem,
        ]);

    }

    public function export(Request $request)
    {
        $request = Session::get('lessonHistory');
        $fileName = "lesson_history_" . date("Y-m-d") . ".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $header = [
            $this->convertShijis("講師コード"),
            $this->convertShijis("レッスン日"),
            $this->convertShijis("レッスン時間"),
            $this->convertShijis("法人・個人"),
            $this->convertShijis("コースタイプ"),
            $this->convertShijis("コース名"),
            $this->convertShijis("レッスン名"),
            $this->convertShijis("テキスト名"),
            $this->convertShijis("講師番号"),
            $this->convertShijis("講師名"),
            $this->convertShijis("学習者番号"),
            $this->convertShijis("学習者名")
        ];


        if (!file_exists(public_path() . '/csv_file/users')) {
            mkdir(public_path() . '/csv_file/users', 0777, true);
        }
        $localPath = public_path() . '/csv_file/users/' . $fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $header);

        $queryBuilder = LessonHistory::select(
            'teacher.teacher_id',
            'teacher.teacher_name',
            'teacher.teacher_code',
            'lesson_schedule.teacher_id',
            'lesson_schedule.lesson_date',
            'lesson_schedule.lesson_starttime',
            'lesson_schedule.lesson_endtime',
            'course.course_name',
            'course.course_type',
            'course.is_for_lms',
            'lesson.lesson_name',
            'lesson_text.lesson_text_name',
            'lesson_history.student_id',
            'student.student_name',
            'lesson_history.lesson_history_id'
        )
            ->join('lesson_schedule', function ($join) {
                $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id');
            })
            ->join('teacher', function ($join) {
                $join->on('lesson_schedule.teacher_id', '=', 'teacher.teacher_id');
            })
            ->join('lesson', function ($join) {
                $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
            })
            ->leftJoin('lesson_text_lesson', function($join) {
                $join->on('lesson.lesson_id', '=', 'lesson_text_lesson.lesson_id');
            })
            ->leftJoin('lesson_text', function($join) {
                $join->on('lesson_text_lesson.lesson_text_id', '=', 'lesson_text.lesson_text_id');
            })
            ->join('student', function ($join) {
                $join->on('lesson_history.student_id', '=', 'student.student_id');
            })
            ->leftJoin('course', function ($join) {
                $join->on('lesson_history.course_id', '=', 'course.course_id');
            })
            ->where('lesson_history.student_lesson_reserve_type', '!=', 2)
            ->orderByDesc('lesson_schedule.lesson_starttime')->groupBy('lesson_schedule.lesson_schedule_id');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where(CommonComponent::escapeLikeSentence('course.course_name', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('lesson.lesson_name', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('lesson_text.lesson_text_name', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_name', $request['search_input']));
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

        $dataExport = $queryBuilder->get()->map(function ($item, $key) {
            $item['lesson_date'] = DateTimeComponent::getDate($item['lesson_date']);
            $item['lesson_starttime'] = DateTimeComponent::getStartEndTime($item['lesson_starttime'], $item['lesson_endtime']);
            $item['course_type'] = $item['course_type'] == CourseTypeEnum:: REGULAR_COURSE ? "プライベート" : "グループ";
            $item['is_for_lms'] = $item['is_for_lms'] == 0 ? "個人" : "法人";
            return $item;
        });

        $input = [];
        foreach ($dataExport as $item) {
            $input['teacher_code'] = $this->convertShijis($item['teacher_code']);
            $input['lesson_date'] = $this->convertShijis($item['lesson_date']);
            $input['lesson_starttime'] = $this->convertShijis($item['lesson_starttime']);
            $input['is_for_lms'] = $this->convertShijis($item['is_for_lms']);
            $input['course_type'] = $this->convertShijis($item['course_type']);
            $input['course_name'] = $this->convertShijis($item['course_name']);
            $input['lesson_name'] = $this->convertShijis($item['lesson_name']);
            $input['lesson_text_name'] = $this->convertShijis($item['lesson_text_name']);
            $input['teacher_id'] = $this->convertShijis($item['teacher_id']);
            $input['teacher_name'] = $this->convertShijis($item['teacher_name']);
            $input['student_id'] = $this->convertShijis($item['course_type'] == 'グループ' ? '-' : $item['student_id']);
            $input['student_name'] = $this->convertShijis($item['course_type'] == 'グループ' ? '-' : $item['student_name']);
            fputcsv($file, $input);
        }

        return Response::download(public_path() . '/csv_file/users/' . $fileName, $fileName, $header);
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
