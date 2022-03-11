<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestTopScore extends Model
{
    use HasFactory;

    protected $table = 'test_top_score';
    protected $primaryKey = 'test_top_score_id';
    public $timestamps = false;

    protected $fillable = [
        'test_id',
        'test_parrent_name',
        'test_category_id',
        'category_name',
        'top_score_avg',
    ];
}
