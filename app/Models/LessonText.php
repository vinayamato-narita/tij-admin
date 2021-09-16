<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonText extends Model
{
    use HasFactory, Sortable;
    protected $primaryKey = 'lesson_text_id';

    protected $table = 'lesson_text';

    public $timestamps = false;
}
