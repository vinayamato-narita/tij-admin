<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class File extends Model
{
    use HasFactory, Sortable;

    protected $table = 'file';

    public $timestamps = false;

    protected $primaryKey = 'file_id';
    protected $appends = array('azure_storage_path');


    public function getAzureStoragePathAttribute()
    {
        return env('AZURE_STORAGE_URL') .  '/' . $this->file_path;
    }
}
