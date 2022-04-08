<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestComment extends Model
{
    use HasFactory;
    protected $table = 'test_comment';

    public $timestamps = false;

    protected $primaryKey = 'test_comment_id';

    public function testResult()
    {
        return $this->hasOne('\App\Models\TestResult', 'test_result_id', 'test_result_id');
    }

}
