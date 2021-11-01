<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonSchedule extends Model
{
    use HasFactory, Sortable;

    public function teacher() 
    {
    	return $this->belongsTo(Teacher::class);
    }

    public function lessonHistory() 
    {
        return $this->belongsTo(LessonHistory::class)->withDefault(function ($lessonHistory, $lessonSchedule) {
            $lessonHistory->student_lesson_reserve_type != 2;
        });
    }
}
