<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSubQuestionTag extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'test_sub_question_tag_id';

    protected $table = 'test_sub_question_tag';
}
