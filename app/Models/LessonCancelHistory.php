<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonCancelHistory extends Model
{
    use HasFactory, Sortable;

    protected $table = 'lesson_cancel_history';

    public $timestamps = false;

    protected $primaryKey = 'lesson_cancel_history_id';
    
    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher', 'teacher_id', 'teacher_id');
    }

    public function student()
    {
        return $this->hasOne('App\Models\Student', 'student_id', 'student_id');
    }

}
