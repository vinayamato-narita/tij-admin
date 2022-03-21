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

    protected $fillable = ['teacher_info_id', 'teacher_id', 'teacher_name', 'teacher_nickname', 'teacher_introduction', 'introduce_from_admin', 'teacher_university', 'teacher_department', 'lang_type'];

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher', 'teacher_id', 'teacher_id');
    }

}
