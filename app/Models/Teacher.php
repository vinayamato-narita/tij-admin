<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Teacher extends Model
{
    use HasFactory, Sortable;

    protected $table = 'teachers';

    public $timestamps = false;

    protected $fillable = [];

    public function timeZone()
    {
        return $this->hasOne('App\Models\TimeZone', 'id', 'timezone_id');
    }

    public function lesson()
    {
        return $this->belongsToMany('App\Models\Lesson', 'teacher_lesson' ,
            'teacher_id', 'lesson_id', 'id', 'id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
