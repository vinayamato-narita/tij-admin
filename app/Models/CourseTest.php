<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTest extends Model
{
    use HasFactory;

    protected $table = 'course_test';

    public $timestamps = false;

    protected $primaryKey = 'course_test_id';
}
