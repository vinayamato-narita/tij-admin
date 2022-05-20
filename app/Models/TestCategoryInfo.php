<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCategoryInfo extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $table = 'test_category_info';

    protected $primaryKey = 'test_category_info_id';
}
