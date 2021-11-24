<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonInfo extends Model
{
    use HasFactory;

    protected $table = 'lesson_info';

    public $timestamps = false;

    protected $primaryKey = 'lesson_info_id';

    protected $fillable = ['lesson_info_id', 'lesson_id', 'lesson_name', 'lesson_description', 'lang_type'];
}
