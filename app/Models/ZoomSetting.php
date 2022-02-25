<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'zoom_setting_id',
        'join_before_host',
        'auto_recording',
        'waiting_room'
    ];
}
