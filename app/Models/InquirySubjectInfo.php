<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquirySubjectInfo extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'faq_id', 'inquiry_subject', 'lang_type'];
}
