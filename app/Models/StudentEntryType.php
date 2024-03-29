<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class StudentEntryType extends Model
{
    use HasFactory, Sortable;

    protected $table = 'student_entry_type';

    public $timestamps = false;

    protected $primaryKey = 'student_entry_type_id';
    
}
