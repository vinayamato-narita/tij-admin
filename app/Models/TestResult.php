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

    public $sortable = ['student.student_id', 'student.student_name', 'test.test_name', 'test_start_time', 'testComment.comment_start_time', 'custom_status'];

    public function customStatusSortable( Builder $query, $direction)
    {
        return $query->join('test', 'test.test_id', '=', 'test_result.test_id')
            ->join('student', 'student.student_id', '=', 'test_result.student_id')
            ->leftJoin('test_comment', 'test_comment.test_result_id', '=', 'test_result.test_result_id')
            ->orderBy('test_comment.test_comment_id', $direction)
            ->orderBy('test_comment.comment_end_time', $direction)
            ->select(['test.*', 'test_comment.*', 'student.*'])
            ;
    }

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



}
