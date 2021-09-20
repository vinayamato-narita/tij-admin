<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Lesson extends Model
{
    use HasFactory, Sortable;
    protected $primaryKey = 'lesson_id';


    protected $table = 'lesson';

    public $timestamps = false;

    public function lessonText()
    {
        return $this->belongsToMany('App\Models\lessonText', 'lesson_text_lesson' ,'lesson_id', 'lesson_text_id');
    }
}
