<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNewsInfo extends Model
{
    use HasFactory;

    protected $table = 'admin_news_info';
    
    public $timestamps = false;
    
    protected $primaryKey = 'news_id';
    
    protected $fillable = ['admin_news_info_id', 'news_id', 'news_title', 'news_body', 'lang_type'];
}
