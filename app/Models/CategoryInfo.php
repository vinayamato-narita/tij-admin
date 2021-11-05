<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryInfo extends Model
{
    use HasFactory;

    protected $table = 'category_info';

    public $timestamps = false;

    protected $primaryKey = 'category_info_id';

    protected $fillable = ['category_info_id', 'category_id', 'category_name', 'lang_type'];



}
