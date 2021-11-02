<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeZone extends Model
{
    use HasFactory;
    protected $table = 'timezone';

    public $timestamps = false;

    protected $primaryKey = 'timezone_id';
    
    protected $fillable = [];
}
