<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquirySubjectInfo extends Model
{
    use HasFactory;

    protected $table = 'inquiry_subject_info';

    public $timestamps = false;

    protected $primaryKey = 'inquiry_subject_info_id';
    
    protected $fillable = ['inquiry_subject_info_id', 'inquiry_subject_id', 'inquiry_subject', 'lang_type'];
}
