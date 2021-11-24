<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Review extends Model
{
    use HasFactory, Sortable;

    protected $table = 'review';

    public $timestamps = false;

    protected $primaryKey = 'review_id';

    public function file()
    {
        return $this->hasOne('App\Models\File', 'file_id', 'file_id');
    }

}
