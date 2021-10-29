<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class FaqCategory extends Model
{
    use HasFactory, Sortable;

    protected $table = 'faq_category';

    public $timestamps = false;

    protected $primaryKey = 'faq_category_id';
}
