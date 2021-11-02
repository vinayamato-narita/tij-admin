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
    
    public $sortable = ['timing_minutes'];
    public function sendRemindMailTiming() {
        return $this->hasOne('App\Models\SendRemindMailTiming', 'send_remind_mail_timing_type', 'send_remind_mail_timing_type');
    }

    public static function getRemindmailPatternInfo($type, $langType) {
    	return DB::table('send_remind_mail_patterns')->select(DB::raw("COALESCE(send_remind_mail_pattern_infos.mail_subject, send_remind_mail_patterns.mail_subject) AS mail_subject"),
            DB::raw("COALESCE(send_remind_mail_pattern_infos.mail_body, send_remind_mail_patterns.mail_body) AS mail_body")
        )
            ->leftJoin('send_remind_mail_pattern_infos', function($join) use($langType) {
                $join->on('send_remind_mail_patterns.id', '=', 'send_remind_mail_pattern_infos.send_remind_mail_pattern_id')
                	->where('send_remind_mail_pattern_infos.lang_type', $langType);
            })
            ->where('send_remind_mail_timing_type', $type)
            ->first();
    }
}
