<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Components\BreadcrumbComponent;
use App\Models\LessonHistory;
use Log;

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
        $queryBuilder = LessonHistory::select('lesson_histories.student_id as student_id', 'teacher_rating', 'teacher_attitude',
            'teacher_punctual', 'skype_voice_rating_from_student', 'comment_from_student_to_office', 'skype_voice_rating_from_teacher', 'comment_from_teacher_to_student', 'comment_from_teacher_to_office', 'note_from_student_to_teacher', 'courses.course_name as course_name',
            'lesson_schedules.lesson_starttime as lesson_starttime', 'lesson_schedules.lesson_endtime as lesson_endtime', 'students.student_nickname as student_nickname', 'teachers.teacher_nickname as teacher_nickname')
            ->leftJoin('students', function($join) {
                $join->on('lesson_histories.student_id', '=', 'students.id');
            })
            ->leftJoin('courses', function($join) {
                $join->on('lesson_histories.course_id', '=', 'courses.course_id');
            })
            ->leftJoin('lesson_schedules', function($join) {
                $join->on('lesson_histories.lesson_schedule_id', '=', 'lesson_schedules.id');
            })
            ->leftJoin('teachers', function($join) {
                $join->on('lesson_schedules.teacher_id', '=', 'teachers.id');
            })
            ->where(function($query) {
                $query->orWhere('lesson_histories.teacher_rating', '<>', 0)
                    ->orWhere('lesson_histories.skype_voice_rating_from_student', '<>', 0)
                    ->orWhere('lesson_histories.teacher_attitude', '<>', 0)
                    ->orWhere('lesson_histories.teacher_punctual', '<>', 0)
                    ->orWhere('lesson_histories.comment_from_student_to_office', '<>', "")
                    ->orWhere('lesson_histories.comment_from_teacher_to_student', '<>', "")
                    ->orWhere('lesson_histories.comment_from_teacher_to_office', '<>', "")
                    ->orWhere('lesson_histories.note_from_student_to_teacher', '<>', "");
            });
            

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student_nickname', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher_nickname', $request['search_input']));
            });
        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "student_nickname") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('students.student_nickname','ASC') : $queryBuilder->orderBy('students.student_nickname','DESC');
            }
            if ($request['sort'] == "course_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('courses.course_name','ASC') : $queryBuilder->orderBy('courses.course_name','DESC');
            }
        }
        $commentList = $queryBuilder->sortable(['id' => 'desc'])->paginate($pageLimit);

        return view('comment.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'commentList' => $commentList,
        ]);
    }
}
