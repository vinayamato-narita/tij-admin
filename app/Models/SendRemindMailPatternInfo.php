<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendRemindMailPatternInfo extends Model
{
    use HasFactory;
    
    protected $table = 'send_remind_mail_pattern_info';

    public $timestamps = false;

    protected $primaryKey = 'send_remind_mail_pattern_info_id';

    protected $fillable = ['send_remind_mail_pattern_info_id', 'send_remind_mail_pattern_id', 'mail_subject', 'mail_body', 'lang_type'];
}
