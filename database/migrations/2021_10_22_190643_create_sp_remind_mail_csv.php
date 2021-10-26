<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateSpRemindMailCsv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $procedure1 = "CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_remind_mail_csv`(IN _is_lms_user INT)
BEGIN
  SELECT
    srmp.mail_subject
		,COALESCE(srmpe.mail_subject, srmp.mail_subject) AS mail_subject_en
		,COALESCE(srmpv.mail_subject, srmp.mail_subject) AS mail_subject_vn
		,srmp.mail_body
		,COALESCE(srmpe.mail_body, srmp.mail_body) AS mail_body_en
		,COALESCE(srmpv.mail_body, srmp.mail_body) AS mail_body_vn
    ,send_remind_mail_timing_type
  FROM
    send_remind_mail_patterns srmp
		LEFT JOIN send_remind_mail_pattern_infos srmpe 
			ON srmpe.send_remind_mail_pattern_id = srmp.id AND srmpe.lang_type = 'en'
		LEFT JOIN send_remind_mail_pattern_infos srmpv 
			ON srmpv.send_remind_mail_pattern_id = srmp.id AND srmpv.lang_type = 'vn'
  WHERE
		CASE WHEN _is_lms_user = 0 THEN
			send_remind_mail_timing_type IN (27,28)
		ELSE send_remind_mail_timing_type IN (29,30,31,32,44)
		END
  ;
END";
        \DB::unprepared($procedure1);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sp_remind_mail_csv');
    }
}
