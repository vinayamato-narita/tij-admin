<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseGroupMemo extends Model
{
    use HasFactory;
    
    protected $table = 'course_group_memo';

    public $timestamps = false;
    
    protected $primaryKey = 'course_group_memo_id';

    protected $fillable = ['course_group_memo_id', 'course_id', 'teacher_id', 'memo', 'last_update_date'];
}
