<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestResult extends Model
{

    public $timestamps = false;

    protected $table = 'test_result';

    protected $primaryKey = 'test_result_id';


}
