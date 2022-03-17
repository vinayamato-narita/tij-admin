<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class TeacherInfo extends Model
{
    use HasFactory, Sortable;
    public $timestamps = false;
    protected $primaryKey = 'teacher_info_id';

    protected $table = 'teacher_info';

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher', 'teacher_id', 'teacher_id');
    }

}
