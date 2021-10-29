<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    use HasFactory;
    protected $table = 'course_video';

    public $timestamps = false;
    
    protected $primaryKey = 'course_video_id';
}
