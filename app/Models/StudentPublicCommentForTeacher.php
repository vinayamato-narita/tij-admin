<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;


class StudentPublicCommentForTeacher extends Authenticatable
{
    use HasFactory, Sortable, SoftDeletes;

    public function student() 
    {
    	return $this->belongsTo(Student::class);
    }

    public function teacher() 
    {
    	return $this->belongsTo(Teacher::class);
    }
}
