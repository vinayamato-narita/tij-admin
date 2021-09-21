<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Enums\InquiryFlag;

class AdminInquiry extends Model
{
    use HasFactory, Sortable;

    public function student() 
    {
    	return $this->belongsTo(Student::class);
    }
    public function getInquiryFlagNameAttribute($value) {
        return InquiryFlag::getDescription($this->inquiry_flag);
    }
}
