<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonHistoryOld extends Model
{
    use HasFactory;

    protected $table = 'lesson_history_old';

    public $timestamps = false;
}
