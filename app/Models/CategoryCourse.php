<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCourse extends Model
{
    use HasFactory;
    
    protected $table = 'category_course';

    public $timestamps = false;
    
    protected $primaryKey = 'category_course_id';
}
