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
    
    public $timestamps = false;

    protected $primaryKey = 'inquiry_id';
    
    public function student() 
    {
    	return $this->belongsTo(Student::class, 'student_id');
    }
    public function getInquiryFlagNameAttribute($value) {
        return InquiryFlag::getDescription($this->inquiry_flag);
    }
}
