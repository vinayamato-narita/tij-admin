<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSpGetMailBuyCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "
        DROP PROCEDURE IF EXISTS `sp_get_mail_when_buy_course_success_lms`;
        CREATE PROCEDURE `sp_get_mail_when_buy_course_success_lms`(IN _order_id VARCHAR(27),
                IN _payment_date VARCHAR(255),
                IN _project_id INT,
                IN _lang_type VARCHAR(10))
        BEGIN
            SELECT
                od.order_id,
                od.course_id,
                COALESCE(csi.course_name, cs.course_name) AS course_name,
                cs.expire_day,
                cs.point_count,
                st.student_email,
                st.student_name,
                st.postcode,
                st.prefecture_number,
                st.student_address,
                st.student_address1,
                st.student_address2,
                st.student_address3,
                lp.prefecture_name,
                DATE_ADD(IF(_payment_date = '',  NOW(), DATE_FORMAT(_payment_date,'%Y-%m-%d 23:59:59')), INTERVAL cs.expire_day DAY) AS expire_date,
                lmscs.price,
                lmscs.start_date_option,
                lmscss.start_date,
                lmscss.expired_date,
                lmscss.course_begin_month,
                psh.point_subscription_history_id,
                psh.payment_way,
                pt.payment_type_name
                 FROM
                `order` AS od
                LEFT JOIN lms_project_course AS lmscs
                   ON od.course_id = lmscs.course_id
                LEFT JOIN course AS cs
                   ON od.course_id = cs.course_id
                 LEFT JOIN course_info AS csi
                    ON csi.course_id = cs.course_id AND csi.lang_type = _lang_type
                LEFT JOIN student AS st
                   ON od.student_id = st.student_id
                LEFT JOIN point_subscription_history psh
                   ON psh.order_id = _order_id
                LEFT JOIN payment_type pt
                   ON pt.payment_type = psh.payment_way
                LEFT JOIN lms_project_course_student AS lmscss
                   ON lmscss.point_subscription_id = psh.point_subscription_history_id
                LEFT JOIN lms_prefecture AS lp
                   ON lp.prefecture_id = st.prefecture_number
                 WHERE od.order_id = _order_id
                AND lmscs.project_id = _project_id AND lmscs.course_type = 0 -- normal course
                 LIMIT 1;
        END 
        ";

        \DB::unprepared($procedure1);

        $procedure2 = "
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
                cs.point_count,
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
        END 
        ";

        \DB::unprepared($procedure2);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
