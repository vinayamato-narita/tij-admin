<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixRevisionSpStudentTopPagePointInfo1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_student_top_page_point_info_1`;
        CREATE PROCEDURE `sp_student_top_page_point_info_1`(
            IN _student_id Int,
            IN _lang_type varchar(10)
            )
            BEGIN

            SET @timezone_id = COALESCE((SELECT timezone_id FROM student WHERE student_id = _student_id),1);
            SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM timezone tz WHERE tz.timezone_id = 1 LIMIT 1),9 * 60);
            SET @time_diff_to = COALESCE((SELECT diff_time * 60 FROM timezone WHERE timezone_id = @timezone_id),9 * 60);

               SELECT SUM(p.point_count) as remain_point,
                      COALESCE(psh.point_expire_date, p.expire_date) as expire_date,
                      DATE_FORMAT(DATE_ADD(DATE_ADD(COALESCE(psh.point_expire_date, p.expire_date),INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y/%m/%d %H:%i:%s')  AS expire_date_timezone,
                      p.course_id,
                      COALESCE(ci.course_name, c.course_name) AS course_name,
                      COUNT(h.lesson_schedule_id) as studied_lessons,
                      c.course_type,
                      c.course_start_date,
                      c.group_lesson_status,
                      c.decide_date,
                      p.point_subscription_id,
                      ls.lesson_starttime,
                      ls.lesson_endtime
               FROM student_point_history p
               LEFT JOIN course c ON p.course_id = c.course_id
               LEFT JOIN course_info ci on ci.course_id = c.course_id and ci.lang_type = _lang_type
               LEFT JOIN lesson_schedule ls on ls.lesson_schedule_id = p.lesson_schedule_id
               LEFT JOIN lesson_history h on h.lesson_schedule_id = p.lesson_schedule_id and student_lesson_reserve_type = 3
               LEFT JOIN point_subscription_history psh ON psh.point_subscription_history_id = p.point_subscription_id
               WHERE p.student_id = _student_id
                AND p.paid_status = 1
                AND TIMESTAMP(expire_date) >= TIMESTAMP(NOW())
                AND psh.payment_status = 1
               GROUP BY DATE_FORMAT(expire_date,'%Y/%m/%d'), p.point_subscription_id
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
