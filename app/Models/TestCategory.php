<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCategory extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $table = 'test_category';

    protected $primaryKey = 'test_category_id';
}
