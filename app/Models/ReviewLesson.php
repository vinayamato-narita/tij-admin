<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ReviewLesson extends Model
{
    use HasFactory, Sortable;

    public $timestamps = false;
    protected $primaryKey = 'review_lesson_id';

    protected $table = 'review_lesson';
}
