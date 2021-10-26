<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonCancelHistory extends Model
{
    use HasFactory, Sortable;

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher', 'teacher_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id', 'id');
    }

}
