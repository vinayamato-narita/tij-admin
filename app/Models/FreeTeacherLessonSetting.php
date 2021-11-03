<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeTeacherLessonSetting extends Model
{
    use HasFactory;

    protected $table = 'free_teacher_lesson_setting';

    public $timestamps = false;

    protected $primaryKey = 'setting_id';
}
