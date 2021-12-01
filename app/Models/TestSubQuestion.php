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

}
