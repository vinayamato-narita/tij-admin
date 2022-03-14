<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ZoomAccount extends Model
{
    use HasFactory, Sortable;
    public $timestamps = false;
    protected $primaryKey = 'zoom_account_id';

    protected $table = 'zoom_account';

    public function zoomSchedules()
    {
        return $this->hasMany('App\Models\ZoomSchedule', 'zoom_account_id', 'zoom_account_id');
    }
}
