<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSubQuestion extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'test_sub_question_id';

    protected $table = 'test_sub_question';
    public function file()
    {
        return $this->hasOne('App\Models\File', 'file_id', 'explanation_file_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'test_sub_question_tag' ,
            'test_sub_question_id', 'tag_id', 'test_sub_question_id', 'tag_id');
    }

    public function testCategory()
    {
        return $this->belongsTo('App\Models\TestSubQuestionCategory', 'test_sub_question_id' , 'test_sub_question_id');
    }

    public function testCategories()
    {
        return $this->belongsToMany('App\Models\TestCategory', 'test_sub_question_category' ,
            'test_sub_question_id', 'test_category_id', 'test_sub_question_id', 'test_category_id');
    }

    public function testQuestion()
    {
        return $this->hasOne('App\Models\TestQuestion', 'test_question_id', 'test_question_id');
    }

}
