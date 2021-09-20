<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonText extends Model
{
    use HasFactory, Sortable;
    protected $table = 'lesson_texts';

    public $timestamps = false;
    public function lesson()
    {
        return $this->belongsToMany('App\Models\Lessons', 'lesson_text_lesson' ,'lesson_id', 'lesson_text_id', 'id', 'id');
    }
}
