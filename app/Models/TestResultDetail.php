<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResultDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'test_result_detail';

    protected $primaryKey = 'test_result_detail_id';


    public function testSubQuestion()
    {
        return $this->hasOne('\App\Models\TestSubQuestion', 'test_sub_question_id', 'test_sub_question_id');
    }
}
