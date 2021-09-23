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
            });

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student_nickname', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher_nickname', $request['search_input']));
            });
        }

        $commentList = $queryBuilder->orderByDesc('lesson_schedules.lesson_starttime')->paginate($pageLimit);

        return view('comment.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'commentList' => $commentList,
        ]);
    }
}
