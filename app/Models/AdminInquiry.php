<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Enums\InquiryFlag;

class AdminInquiry extends Model
{
    use HasFactory, Sortable;

    protected $table = 'admin_inquiry';
    protected $primaryKey = 'inquiry_id';
    public $timestamps = false;

    public function student() 
    {
    	return $this->belongsTo(Student::class, 'user_id', 'student_id');
    }
    public function getInquiryFlagNameAttribute($value) {
        return InquiryFlag::getDescription($this->inquiry_flag);
    }
}
