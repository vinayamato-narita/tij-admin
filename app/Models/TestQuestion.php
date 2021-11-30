<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class TestQuestion extends Model
{
    use HasFactory, Sortable;
    public $timestamps = false;
    protected $primaryKey = 'test_question_id';

    protected $table = 'test_question';
    public function testSubQuestions()
    {
        return $this->hasMany('App\Models\TestSubQuestion', 'test_question_id', 'test_question_id');
    }


}
