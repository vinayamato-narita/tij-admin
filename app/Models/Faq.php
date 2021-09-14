<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Faq extends Model
{
    use HasFactory, Sortable;

    protected $table = 'faq';
    protected $primaryKey = 'faq_id';
    public $timestamps = false;

    public function faqCategory() 
    {
    	return $this->belongsTo(FaqCategory::class, 'faq_category_id', 'faq_category_id');
    }
}
