<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCampaign extends Model
{
    use HasFactory;
    protected $table = 'course_campaign';

    public $timestamps = false;
}
