<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Teacher extends Model
{
    use HasFactory, Sortable;

    protected $table = 'teacher';

    public $timestamps = false;

    protected $fillable = [];

}
