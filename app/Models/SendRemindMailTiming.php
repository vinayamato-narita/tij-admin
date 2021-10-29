<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class SendRemindMailTiming extends Model
{
    use HasFactory, Sortable;

    protected $table = 'send_remind_mail_timing';

    public $timestamps = false;

    protected $primaryKey = 'send_remind_mail_timing_type';
    
    public $sortable = ['send_remind_mail_timing_type_name'];
}
