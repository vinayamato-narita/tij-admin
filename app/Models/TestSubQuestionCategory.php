<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestSubQuestionCategory extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;

    protected $table = 'test_sub_question_category';
    protected $primaryKey = 'test_sub_question_category_id';


}
