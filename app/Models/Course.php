<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Course extends Model
{
    use HasFactory, Sortable;

    protected $primaryKey = 'course_id';
    public function childCourse()
    {
        return $this->belongsToMany('App\Models\Course', 'course_set_course' ,
            'set_course_id', 'course_id', 'course_id', 'course_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'course_tag' ,
            'course_id', 'tag_id', 'course_id', 'id');
    }

    public function lesson()
    {
        return $this->belongsToMany('App\Models\Lesson', 'course_lesson' ,
            'course_id', 'lesson_id', 'course_id', 'id');
    }


    public function getSumAmountAttribute(){
        if (!$this->is_set_course) return $this->amount;
        return $this->childCourse()->sum('amount');
    }





}
