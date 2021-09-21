<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Faq extends Model
{
    use HasFactory, Sortable;

    public function faqCategory() 
    {
    	return $this->belongsTo(FaqCategory::class);
    }
}
