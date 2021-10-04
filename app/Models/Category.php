<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Category extends Model
{
    use HasFactory, Sortable;
    public function courses()
    {
        return $this->belongsToMany('App\Models\course', 'category_course' ,
            'category_id', 'course_id', 'id', 'course_id');
    }
}
