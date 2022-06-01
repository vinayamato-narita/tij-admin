<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSpStudentGetPaymentList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_student_get_payment_list`;
        CREATE PROCEDURE `sp_student_get_payment_list`(
            IN _student_id INT,
          IN _lang_type varchar(10)
        )
        BEGIN
            SET @timezone_id = COALESCE((SELECT timezone_id FROM student WHERE student_id = _student_id),1);
            SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM timezone tz WHERE tz.timezone_id = 1 LIMIT 1),9 * 60);
            SET @time_diff_to = COALESCE((SELECT diff_time * 60 FROM timezone WHERE timezone_id = @timezone_id),9 * 60);

        SELECT
            c.course_id
            ,psh.amount
            ,psh.tax
            ,DATE_FORMAT(DATE_ADD(DATE_ADD(psh.payment_date,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y/%m/%d') AS payment_date
            ,psh.point_expire_date AS  point_expire_date
            ,DATE_FORMAT(DATE_ADD(DATE_ADD(psh.begin_date,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y/%m/%d')   AS begin_date
            ,DATE_FORMAT(DATE_ADD(DATE_ADD(psh.point_expire_date,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y/%m/%d')   AS point_expire_date
            ,COALESCE(ci.course_name, c.course_name) AS course_name
            ,c.course_type
            ,psh.point_subscription_history_id
        FROM
            point_subscription_history psh
            LEFT JOIN course c ON c.course_id = psh.course_id
            LEFT JOIN course_info ci ON ci.course_id = c.course_id AND ci.lang_type = _lang_type
            LEFT JOIN student s
                 ON psh.student_id = s.student_id
        WHERE
            psh.student_id = _student_id
            AND psh.del_flag = 0
            AND psh.payment_status = 1
        ORDER BY
            psh.payment_date DESC
        ;
        END";

        \DB::unprepared($procedure);
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
