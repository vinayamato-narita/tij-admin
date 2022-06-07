<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Components\BreadcrumbComponent;
use App\Models\LessonHistory;
use App\Models\StudentPointHistory;
use Log;
use DB;

class CommentController extends BaseController
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
            ['name' => 'comment_list']
        ]);
        $pageLimit = $this->newListLimit($request);
            
        $queryBuilder = StudentPointHistory::select('student.student_id as student_id', 'teacher_rating', 'teacher_attitude',
            'teacher_punctual', 'skype_voice_rating_from_student', 'comment_from_student_to_office', 'skype_voice_rating_from_teacher', 'comment_from_teacher_to_student', 'comment_from_teacher_to_office', 'note_from_student_to_teacher', 'course.course_name as course_name',
            'lesson_schedule.lesson_starttime as lesson_starttime', 'lesson_schedule.lesson_endtime as lesson_endtime', 'student.student_nickname as student_nickname', 'teacher.teacher_nickname as teacher_nickname', 'student_point_history.student_point_history_id',
            DB::raw('(CASE WHEN lesson_schedule.lesson_starttime IS NULL AND lesson_schedule.lesson_endtime IS NULL THEN "" ELSE CONCAT(DATE_FORMAT(lesson_schedule.lesson_starttime, "%Y-%m-%d %H:%i"), "~", DATE_FORMAT(lesson_schedule.lesson_endtime, "%H:%i"))  END) as lesson_time'))
            ->join('student', function($join) {
                $join->on('student_point_history.student_id', '=', 'student.student_id');
            })
            ->join('course', function($join) {
                $join->on('student_point_history.course_id', '=', 'course.course_id');
            })
            ->join('lesson_schedule', function($join) {
                $join->on('student_point_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id');
            })
            ->join('teacher', function($join) {
                $join->on('lesson_schedule.teacher_id', '=', 'teacher.teacher_id');
            })
            ->leftJoin('lesson_history', function($join) {
                $join->on('lesson_schedule.lesson_schedule_id', '=', 'lesson_history.lesson_schedule_id');;
            })
            ->where(function($query) {
                $query->orWhere('lesson_history.teacher_rating', '<>', 0)
                    ->orWhere('lesson_history.skype_voice_rating_from_student', '<>', 0)
                    ->orWhere('lesson_history.teacher_attitude', '<>', 0)
                    ->orWhere('lesson_history.teacher_punctual', '<>', 0)
                    ->orWhere('lesson_history.comment_from_student_to_office', '<>', "")
                    ->orWhere('lesson_history.comment_from_teacher_to_student', '<>', "")
                    ->orWhere('lesson_history.comment_from_teacher_to_office', '<>', "")
                    ->orWhere('lesson_history.note_from_student_to_teacher', '<>', "");
            })
            ->orderBy('lesson_time', 'desc');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student_nickname', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher_nickname', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('course_name', $request['search_input']));
            });
        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "student_nickname") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student.student_nickname','ASC') : $queryBuilder->orderBy('student.student_nickname','DESC');
            }
            if ($request['sort'] == "course_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('course.course_name','ASC') : $queryBuilder->orderBy('course.course_name','DESC');
            }
            if ($request['sort'] == "student_id") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student.student_id','ASC') : $queryBuilder->orderBy('student.student_id','DESC');
            }
            if ($request['sort'] == "teacher_rating") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_history.teacher_rating','ASC') : $queryBuilder->orderBy('lesson_history.teacher_rating','DESC');
            }
            if ($request['sort'] == "teacher_attitude") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_history.teacher_attitude','ASC') : $queryBuilder->orderBy('lesson_history.teacher_attitude','DESC');
            }
            if ($request['sort'] == "teacher_punctual") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_history.teacher_punctual','ASC') : $queryBuilder->orderBy('lesson_history.teacher_punctual','DESC');
            }
            if ($request['sort'] == "skype_voice_rating_from_student") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_history.skype_voice_rating_from_student','ASC') : $queryBuilder->orderBy('lesson_history.skype_voice_rating_from_student','DESC');
            }
            if ($request['sort'] == "comment_from_student_to_office") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_history.comment_from_student_to_office','ASC') : $queryBuilder->orderBy('lesson_history.comment_from_student_to_office','DESC');
            }
            if ($request['sort'] == "skype_voice_rating_from_teacher") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_history.skype_voice_rating_from_teacher','ASC') : $queryBuilder->orderBy('lesson_history.skype_voice_rating_from_teacher','DESC');
            }
            if ($request['sort'] == "comment_from_teacher_to_student") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_history.comment_from_teacher_to_student','ASC') : $queryBuilder->orderBy('lesson_history.comment_from_teacher_to_student','DESC');
            }
            if ($request['sort'] == "comment_from_teacher_to_office") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_history.comment_from_teacher_to_office','ASC') : $queryBuilder->orderBy('lesson_history.comment_from_teacher_to_office','DESC');
            }
            if ($request['sort'] == "teacher_nickname") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('teacher.teacher_nickname','ASC') : $queryBuilder->orderBy('teacher.teacher_nickname','DESC');
            }
            if ($request['sort'] == "lesson_time") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('(CASE WHEN lesson_schedule.lesson_starttime IS NULL AND lesson_schedule.lesson_endtime IS NULL THEN "" ELSE CONCAT(DATE_FORMAT(lesson_schedule.lesson_starttime, "%Y-%m-%d %H:%i"), "~", DATE_FORMAT(lesson_schedule.lesson_endtime, "%H:%i"))  END) ASC') : $queryBuilder->orderByRaw('(CASE WHEN lesson_schedule.lesson_starttime IS NULL AND lesson_schedule.lesson_endtime IS NULL THEN "" ELSE CONCAT(DATE_FORMAT(lesson_schedule.lesson_starttime, "%Y-%m-%d %H:%i"), "~", DATE_FORMAT(lesson_schedule.lesson_endtime, "%H:%i"))  END) DESC');
            }
        }
        $commentList = $queryBuilder->sortable(['lesson_starttime' => 'DESC'])->paginate($pageLimit);

        return view('comment.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'commentList' => $commentList,
        ]);
    }
}
