<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForGetRemindmailByType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DELIMITER //
        DROP PROCEDURE IF EXISTS `sp_get_remindmail_by_type`;
        CREATE PROCEDURE `sp_get_remindmail_by_type`(IN _type INT,
            IN _lang_type VARCHAR(10))
        BEGIN
            SELECT
                COALESCE(srmpi.mail_subject, srmp.mail_subject) AS mail_subject,
                COALESCE(srmpi.mail_body, srmp.mail_body) AS mail_body
            FROM
                send_remind_mail_pattern srmp
            LEFT JOIN send_remind_mail_pattern_info srmpi
                ON srmp.send_remind_mail_pattern_id = srmpi.send_remind_mail_pattern_id
                AND srmpi.lang_type = _lang_type
            WHERE
                srmp.send_remind_mail_timing_type = _type
                ;
        END //
        DELIMITER ;
        ";

        \DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_procedure_for_get_remindmail_by_type');
    }
}
