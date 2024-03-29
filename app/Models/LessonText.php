<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonText extends Model
{
    use HasFactory, Sortable;
    protected $table = 'lesson_text';

    public $timestamps = false;

    protected $primaryKey = 'lesson_text_id';
    
    public function lesson()
    {
        return $this->belongsToMany('App\Models\Lesson', 'lesson_text_lesson' , 'lesson_text_id', 'lesson_id', 'lesson_text_id', 'lesson_id');
    }

    public function studentFile()
    {
        return $this->hasOne('App\Models\File','file_id', 'lesson_text_student_file_id');
    }

    public function teacherFile()
    {
        return $this->hasOne('App\Models\File','file_id',  'lesson_text_teacher_file_id');
    }
}
