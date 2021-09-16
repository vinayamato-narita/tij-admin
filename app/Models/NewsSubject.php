<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsSubject extends Model
{
    use HasFactory;

    protected $table = 'news_subject';
    public $timestamps = false;
    protected $primaryKey = 'news_subject_id';
}
