<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LessonTest extends Model
{
    use HasFactory, Sortable;

    protected $table = 'lesson_test';

    public $timestamps = false;

    protected $primaryKey = 'lesson_test_id';

}
