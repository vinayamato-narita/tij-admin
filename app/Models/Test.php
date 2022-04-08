<?php

namespace App\Models;

use App\Enums\TestType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Test extends Model
{
    use HasFactory, Sortable;

    protected $table = 'test';

    public $timestamps = false;

    protected $primaryKey = 'test_id';

    protected $appends = array('type_name');

    public function getTypeNameAttribute()
    {
        return TestType::getDescription($this->test_type);
    }

    public function testQuestions()
    {
        return $this->hasMany('App\Models\TestQuestion', 'test_id', 'test_id')->orderBy('display_order');
    }

    public function courses()
    {
        return $this->belongsToMany('App\Models\Course', 'course_test' , 'test_id', 'course_id', 'test_id', 'course_id');
    }

    public function lessons()
    {
        return $this->belongsToMany('App\Models\Lesson', 'lesson_test' , 'test_id', 'lesson_id', 'test_id', 'lesson_id');
    }


}
