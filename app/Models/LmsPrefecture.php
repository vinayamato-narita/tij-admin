<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LmsPrefecture extends Model
{
    use HasFactory, Sortable;

    protected $table = 'lms_prefecture';

    public $timestamps = false;

    protected $primaryKey = 'prefecture_id';
}
