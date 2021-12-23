<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForGetMailWhenBuyCourseSuccess extends Migration
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
        DROP PROCEDURE IF EXISTS `sp_get_mail_when_buy_course_success`;
        CREATE PROCEDURE `sp_get_mail_when_buy_course_success`(IN _order_id VARCHAR(27),
          IN _payment_date VARCHAR(255),
            IN _lang_type VARCHAR(10))
        BEGIN
            SELECT
                od.order_id,
                od.course_id,
                COALESCE(csi.course_name, cs.course_name) AS course_name,
                cs.amount,
                cs.expire_day,
                st.student_email,
                st.student_name,
                DATE_ADD(IF(_payment_date = '',  NOW(), DATE_FORMAT(_payment_date,'%Y-%m-%d 23:59:59')), INTERVAL cs.expire_day DAY) AS expire_date
            FROM
                `order` AS od
            LEFT JOIN course AS cs
               ON od.course_id = cs.course_id
            LEFT JOIN course_info AS csi
                    ON csi.course_id = cs.course_id AND csi.lang_type = _lang_type
            LEFT JOIN student AS st
               ON od.student_id = st.student_id
             WHERE od.order_id = _order_id
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
        Schema::dropIfExists('sp_get_mail_when_buy_course_success');
    }
}
