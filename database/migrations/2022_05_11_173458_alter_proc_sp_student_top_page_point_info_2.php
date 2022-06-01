<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProcSpStudentTopPagePointInfo2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_student_top_page_point_info_2`;
        CREATE PROCEDURE `sp_student_top_page_point_info_2`(
            IN _student_id Int,
            IN _lang_type varchar(10)
            )
            BEGIN
                SET @timezone_id = COALESCE((SELECT timezone_id FROM student WHERE student_id = _student_id),1);
                SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM timezone tz WHERE tz.timezone_id = 1 LIMIT 1),9 * 60);
                SET @time_diff_to = COALESCE((SELECT diff_time * 60 FROM timezone WHERE timezone_id = @timezone_id),9 * 60);

               SELECT SUM(p.point_count) as remain_point,
                      p.point_expire_date as expire_date,
                      DATE_FORMAT(DATE_ADD(DATE_ADD(p.point_expire_date,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y/%m/%d %H:%i:%s')
                        AS expire_date_timezone,
                      p.course_id,
                      p.point_subscription_history_id,
                      COALESCE(ci.course_name, c.course_name) AS course_name
               FROM point_subscription_history  p
               LEFT JOIN course c ON p.course_id = c.course_id
               LEFT JOIN course_info ci on ci.course_id = c.course_id and ci.lang_type = _lang_type
               WHERE p.student_id = _student_id
                AND p.payment_status = 1
                AND TIMESTAMP(p.point_expire_date) >= TIMESTAMP(NOW())
                AND c.course_type = 2
                
               GROUP BY point_subscription_history_id
               ;
            END
        ";

        DB::unprepared($procedure);
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
