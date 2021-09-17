<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqInfo extends Model
{
    use HasFactory;

    protected $table = 'faq_info';
    public $timestamps = false;
    protected $primaryKey = 'faq_info_id';

    protected $fillable = ['faq_info_id', 'faq_id', 'question', 'answer', 'lang_type'];
}
