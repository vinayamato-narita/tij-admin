<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonTextLesson extends Model
{
    use HasFactory, Sortable;
    
    protected $table = 'lesson_text_lesson';

    public $timestamps = false;

    protected $primaryKey = 'lesson_text_lesson_id';
}
