<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Test extends Model
{
    use HasFactory, Sortable;

    protected $table = 'test';

    public $timestamps = false;

    protected $primaryKey = 'test_id';




    public function testQuestions()
    {
        return $this->hasMany('App\Models\TestQuestion', 'test_id', 'test_id');
    }

}
