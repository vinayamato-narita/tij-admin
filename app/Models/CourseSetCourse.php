<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSetCourse extends Model
{
    use HasFactory;
    protected $table = 'course_set_course';

    public $timestamps = false;
    
    protected $primaryKey = 'course_set_course_id';
}
