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

    protected $primaryKey = 'teacher_id';

    protected $fillable = [];

    public function timeZone()
    {
        return $this->hasOne('App\Models\TimeZone', 'timezone_id', 'timezone_id');
    }

    public function lesson()
    {
        return $this->belongsToMany('App\Models\Lesson', 'teacher_lesson' ,
            'teacher_id', 'lesson_id', 'teacher_id', 'lesson_id');
    }

    public function teacherLesson()
    {
        return $this->hasMany(TeacherLesson::class, 'teacher_id');
    }

    public function teacherInfo()
    {
        return $this->hasOne('App\Models\TeacherInfo', 'teacher_id', 'teacher_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function getAvatarSrcAttribute()
    {
        if (!empty($this->photo_savepath))
            return env('AZURE_STORAGE_ENDPOINT') . '/'. env('AZURE_STORAGE_CONTAINER') . '/' . $this->photo_savepath;
        return null;
    }
}
