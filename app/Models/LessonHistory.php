<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonHistory extends Model
{
    use HasFactory, Sortable;

    protected $table = 'lesson_history';

    public $timestamps = false;

    protected $primaryKey = 'lesson_history_id';
}
