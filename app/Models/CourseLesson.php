<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CourseLesson extends Model
{
    use HasFactory, Sortable;
    protected $table = 'course_lesson';
    public $timestamps = false;

    protected $primaryKey = 'course_lesson_id';

    public function Lesson()
    {
        return $this->hasOne('App\Models\Lesson', 'lesson_id', 'lesson_id');
    }
}
