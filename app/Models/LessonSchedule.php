<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonSchedule extends Model
{
    use HasFactory, Sortable;

    protected $table = 'lesson_schedule';

    public $timestamps = false;

    protected $primaryKey = 'lesson_schedule_id';
}
