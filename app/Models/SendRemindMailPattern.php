<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class SendRemindMailPattern extends Model
{
    use HasFactory, Sortable;
    public $timestamps = false;
    public $sortable = ['timing_minutes'];
    public function sendRemindMailTiming() {
        return $this->hasOne('App\Models\SendRemindMailTiming', 'send_remind_mail_timing_type', 'send_remind_mail_timing_type');
    }
}
