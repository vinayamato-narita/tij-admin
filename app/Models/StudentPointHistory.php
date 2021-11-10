<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class StudentPointHistory extends Model
{
    use HasFactory, Sortable;

    protected $table = 'student_point_history';

    public $timestamps = false;

    protected $primaryKey = 'student_point_history_id';
}