<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpForStudentTop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "
        DROP PROCEDURE IF EXISTS `sp_get_student_news_list_for_top`;
        CREATE PROCEDURE `sp_get_student_news_list_for_top`(
            IN _limit INT,
            IN _lang_type varchar(10)
            )
            BEGIN
              SELECT
                an.news_id
                ,Replace(Replace(coalesce(ani.news_title,an.news_title), Char(13), ''),Char(10), '')  AS news_title
                ,Replace(Replace(COALESCE(ani.news_body,an.news_body), Char(13), ''),Char(10), '')   AS news_body
                ,news_subject_id
                ,news_update_date
              FROM
                admin_news an  
                LEFT JOIN admin_news_info ani ON an.news_id = ani.news_id and ani.lang_type = _lang_type
              WHERE
                news_subject_id IN (1,3)
                AND  an.is_show_on_student_top = 1
                AND an.news_update_date >= DATE_SUB(NOW(), INTERVAL 365 DAY)
              ORDER BY
                news_update_date DESC
              LIMIT _limit;
            END
        ";

        \DB::unprepared($procedure1);

        $procedure2 = "
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
                                cg.category_icon
               FROM student_point_history p
               LEFT JOIN course c ON p.course_id = c.course_id
               LEFT JOIN course_info ci on ci.course_id = c.course_id and ci.lang_type = _lang_type
                 LEFT JOIN category_course cc ON cc.course_id = c.course_id
                 LEFT JOIN category cg ON cg.category_id = cc.category_id
               LEFT JOIN lesson_history h on h.lesson_schedule_id = p.lesson_schedule_id and student_lesson_reserve_type = 3
               WHERE p.student_id = _student_id
                AND DATE(expire_date) >= DATE(NOW())
                AND 
                   CASE 
                      WHEN @current_course > 1
                      THEN p.course_id >1 
                      ELSE 1=1 
                   END
               GROUP BY DATE_FORMAT(expire_date,'%Y/%m/%d'), p.course_id
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
        Schema::dropIfExists('sp_for_student_top');
    }
}
