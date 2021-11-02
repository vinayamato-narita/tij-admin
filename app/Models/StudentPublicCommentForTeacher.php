<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;

class StudentPublicCommentForTeacher extends Authenticatable
{
    use HasFactory, Sortable;

    protected $table = 'student_public_comment_for_teacher';

    public $timestamps = false;

    protected $primaryKey = 'student_public_comment_for_teacher_id';
    
    public function student() 
    {
    	return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher() 
    {
    	return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
