<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Lesson extends Model
{
    use HasFactory, Sortable;

    protected $table = 'lesson';

    public $timestamps = false;

    protected $primaryKey = 'lesson_id';

    public function lessonText()
    {
        return $this->belongsToMany('App\Models\LessonText', 'lesson_text_lesson' ,'lesson_id', 'lesson_text_id', 'lesson_id', 'lesson_text_id');
    }
    public function courseLesson()
    {
        return $this->hasOne('App\Models\CourseLesson', 'lesson_id', 'lesson_id');
    }

}
