<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;

class SendRemindMailPattern extends Model
{
    use HasFactory, Sortable;
    
    protected $table = 'send_remind_mail_pattern';

    public $timestamps = false;

    protected $primaryKey = 'send_remind_mail_pattern_id';
    
    public $sortable = ['timing_minutes', 'sendRemindMailTiming.send_remind_mail_timing_type_name'];
    public function sendRemindMailTiming() {
        return $this->hasOne('App\Models\SendRemindMailTiming', 'send_remind_mail_timing_type', 'send_remind_mail_timing_type');
    }

    public static function getRemindmailPatternInfo($type, $langType) {
    	return DB::table('send_remind_mail_pattern')->select(DB::raw("COALESCE(send_remind_mail_pattern_info.mail_subject, send_remind_mail_pattern.mail_subject) AS mail_subject"),
            DB::raw("COALESCE(send_remind_mail_pattern_info.mail_body, send_remind_mail_pattern.mail_body) AS mail_body")
        )
            ->leftJoin('send_remind_mail_pattern_info', function($join) use($langType) {
                $join->on('send_remind_mail_pattern.send_remind_mail_pattern_id', '=', 'send_remind_mail_pattern_info.send_remind_mail_pattern_id')
                	->where('send_remind_mail_pattern_info.lang_type', $langType);
            })
            ->where('send_remind_mail_timing_type', $type)
            ->get();
    }
}
