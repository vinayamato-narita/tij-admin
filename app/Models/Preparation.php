<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Preparation extends Model
{
    use HasFactory, Sortable;

    protected $table = 'preparation';

    public $timestamps = false;

    protected $primaryKey = 'preparation_id';

    public function file()
    {
        return $this->hasOne('App\Models\File', 'file_id', 'file_id');
    }
}
