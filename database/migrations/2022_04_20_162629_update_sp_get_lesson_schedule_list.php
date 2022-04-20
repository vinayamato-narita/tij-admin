<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSpGetLessonScheduleList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_admin_get_lesson_schedule_list`;
        
        CREATE PROCEDURE `sp_admin_get_lesson_schedule_list`(IN _teacher_id int,
            IN _d char(8))
        BEGIN
            SELECT
                   lesson_starttime as 'lesson_starttime'
                   ,CONCAT(DATE_FORMAT(lesson_starttime,'%H:%i'),'-',DATE_FORMAT(lesson_endtime,'%H:%i')) as 'lesson_time'
                   ,COALESCE(ls.lesson_schedule_id,0) AS lesson_schedule_id
                   ,COALESCE(ls.lesson_id,0) AS lesson_id
                   ,CONCAT(IF (ls.lesson_type_id = 0,'-', 'registered' ), '(',COALESCE(lesson_name,'-'),')') AS lesson_name
									 ,COALESCE(lesson_name,'') as lesson_name_selected
                   ,COALESCE(ls.lesson_type_id,0) AS lesson_type_id
                   ,COALESCE(ls.lesson_text_id,0) AS lesson_text_id
                   ,COALESCE(ls.lesson_subscription_type,0) AS lesson_subscription_type
									 ,s.student_id as student_id
									 ,s.student_name as student_name
									 ,s.student_nickname as student_nickname
									 ,c.course_type as course_type
                 FROM
                   lesson_schedule ls
                   LEFT OUTER JOIN lesson      le ON le.lesson_id = ls.lesson_id
                   LEFT OUTER JOIN lesson_text lx ON lx.lesson_text_id = ls.lesson_text_id
									 LEFT JOIN student_point_history sph ON ls.lesson_schedule_id = sph.lesson_schedule_id
									 LEFT JOIN course c ON sph.course_id = c.course_id
									 LEFT JOIN student s ON sph.student_id = s.student_id
                 WHERE
                   ls.teacher_id = _teacher_id
                   AND DATE_FORMAT(lesson_date,'%Y%m%d') BETWEEN _d AND DATE_FORMAT(DATE_ADD(_d,INTERVAL 6 day),'%Y%m%d')
            ;
            END
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
        //
    }
}
