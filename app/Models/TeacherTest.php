<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class TeacherTest extends Model
{
    use HasFactory, Sortable;

    protected $table = 'teacher_test';

    public $timestamps = false;

    protected $primaryKey = 'teacher_test_id';

    public function teacherTest()
    {
        return $this->hasOne('App\Models\Test', 'test_id', 'test_id');
    }

    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher', 'teacher_id', 'teacher_id');
    }

}
