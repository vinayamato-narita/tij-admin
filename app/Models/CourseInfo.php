<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseInfo extends Model
{
    use HasFactory;

    protected $table = 'course_info';

    public $timestamps = false;

    protected $primaryKey = 'course_info_id';

    protected $fillable = ['category_info_id', 'course_id', 'course_name', 'course_description', 'lang_type', 'course_target', 'course_attainment_target'];
}
