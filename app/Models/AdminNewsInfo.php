<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNewsInfo extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'news_id', 'news_title', 'news_body', 'lang_type'];
}
