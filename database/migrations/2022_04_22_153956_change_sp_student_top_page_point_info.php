<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSpStudentTopPagePointInfo extends Migration
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

               SET @current_course=(SELECT course_id FROM student WHERE student_id =_student_id );

               SELECT SUM(p.point_count) as remain_point,
                      expire_date,
                      p.course_id,
                      COALESCE(ci.course_name, c.course_name) AS course_name,
                      COUNT(h.lesson_schedule_id) as studied_lessons,
                      c.course_type,
                      c.course_start_date,
                      c.group_lesson_status,
                      c.decide_date
               FROM student_point_history p
               LEFT JOIN course c ON p.course_id = c.course_id
               LEFT JOIN course_info ci on ci.course_id = c.course_id and ci.lang_type = _lang_type
                 LEFT JOIN category_course cc ON cc.course_id = c.course_id
                 LEFT JOIN category cg ON cg.category_id = cc.category_id
               LEFT JOIN lesson_history h on h.lesson_schedule_id = p.lesson_schedule_id and student_lesson_reserve_type = 3
               WHERE p.student_id = _student_id
                AND p.paid_status = 1
                AND TIMESTAMP(p.expire_date) >= TIMESTAMP(NOW())
                AND
                   CASE
                      WHEN @current_course > 1
                      THEN p.course_id >1
                      ELSE 1=1
                   END
               GROUP BY DATE_FORMAT(expire_date,'%Y/%m/%d'), p.point_subscription_id
               ;
            END
        ";

        DB::unprepared($procedure);

        $procedure1 = "
        DROP PROCEDURE IF EXISTS `sp_student_top_page_point_info_2`;
        CREATE PROCEDURE `sp_student_top_page_point_info_2`(
            IN _student_id Int,
            IN _lang_type varchar(10)
            )
            BEGIN

               SET @current_course=(SELECT course_id FROM student WHERE student_id =_student_id );

               SELECT SUM(p.point_count) as remain_point,
                      p.point_expire_date as expire_date,
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
                AND
                   CASE
                      WHEN @current_course > 1
                      THEN p.course_id >1
                      ELSE 1=1
                   END
               ;
            END
        ";

        DB::unprepared($procedure1);
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
