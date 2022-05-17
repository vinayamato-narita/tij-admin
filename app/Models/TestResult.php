<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class TestResult extends Model
{
    use  Sortable;

    public $timestamps = false;

    protected $table = 'test_result';

    protected $primaryKey = 'test_result_id';

    public function student()
    {
        return $this->hasOne('App\Models\Student', 'student_id', 'student_id');
    }

    public function test()
    {
        return $this->hasOne('App\Models\Test', 'test_id', 'test_id');
    }

    public function test_comment()
    {
        return $this->belongsTo('App\Models\TestComment', 'test_result_id', 'test_result_id');
    }

    public function course()
    {
        return $this->hasOne('App\Models\Course', 'course_id', 'course_id');
    }



}
