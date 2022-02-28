<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomSetting extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'zoom_setting_id';

    protected $table = 'zoom_setting';

}
