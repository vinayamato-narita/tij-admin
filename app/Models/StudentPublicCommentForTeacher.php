<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;

class StudentPublicCommentForTeacher extends Authenticatable
{
    use HasFactory, Sortable;

    public function student() 
    {
    	return $this->belongsTo(Student::class);
    }

    public function teacher() 
    {
    	return $this->belongsTo(Teacher::class);
    }
}
