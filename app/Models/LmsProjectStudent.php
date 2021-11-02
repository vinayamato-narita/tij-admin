<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LmsProjectStudent extends Model
{
    use HasFactory, Sortable;

    protected $table = 'lms_project_student';

    public $timestamps = false;

    protected $primaryKey = 'project_student_id';
}
