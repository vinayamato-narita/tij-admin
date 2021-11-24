<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class PreparationLesson extends Model
{
    use HasFactory, Sortable;

    public $timestamps = false;
    protected $primaryKey = 'preparation_lesson_id';

    protected $table = 'preparation_lesson';
}
