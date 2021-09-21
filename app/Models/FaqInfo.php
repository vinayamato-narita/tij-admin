<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqInfo extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'faq_id', 'question', 'answer', 'lang_type'];
}
