<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonSchedule extends Model
{
    use HasFactory, Sortable;

    protected $table = 'lesson_schedule';

    public $timestamps = false;

    public $sortable = ['course.course_name', 'lesson.lesson_name', 'teacher.teacher_name', 'lesson_starttime'];

    protected  $sortableAs = ['student_point_history_count', 'lesson_histories_count'];


    public function studentPointHistory()
    {
        return $this->hasMany(StudentPointHistory::class, 'lesson_schedule_id', 'lesson_schedule_id')
            ->where('paid_status', 1)->distinct('student_id');
    }

    public function lessonHistories()
    {
        return $this->hasMany(LessonHistory::class, 'lesson_schedule_id', 'lesson_schedule_id')
            ->distinct('student_id');
    }

    protected $primaryKey = 'lesson_schedule_id';

    public function teacher() 
    {
    	return $this->hasOne(Teacher::class, 'teacher_id', 'teacher_id');
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'course_id', 'course_id');
    }

    public function lesson()
    {
        return $this->hasOne(Lesson::class, 'lesson_id', 'lesson_id');
    }

    public function lessonHistory() 
    {
        return $this->belongsTo(LessonHistory::class)->withDefault(function ($lessonHistory, $lessonSchedule) {
            $lessonHistory->student_lesson_reserve_type != 2;
        });
    }
}
