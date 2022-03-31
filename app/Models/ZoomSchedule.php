<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomSchedule extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'zoom_schedule_id';

    protected $table = 'zoom_schedule';

    public function zoomAccount()
    {
        return $this->belongsTo('App\Models\ZoomAccount', 'zoom_account_id', 'zoom_account_id');
    }
}
