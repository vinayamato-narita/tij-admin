<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSpGetLessonScheduleListGetLessonName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "
        DROP PROCEDURE IF EXISTS `sp_get_lesson_schedule_list`;
        CREATE PROCEDURE `sp_get_lesson_schedule_list`(IN _teacher_id int,
            IN _d char(8),
            IN _lang_type int)
BEGIN
        SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM  timezone tz WHERE tz.timezone_id = 1 LIMIT 1),9 * 60);
				
        SELECT
							 DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE) AS lesson_starttime
               ,CONCAT(DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE),'%H:%i'),'-',DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_endtime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE),'%H:%i')) AS 'lesson_time'
               ,COALESCE(ls.lesson_schedule_id,0) AS lesson_schedule_id
               ,COALESCE(ls.lesson_id,0) AS lesson_id
							 ,(CASE 
													WHEN ls.lesson_type_id = 0 AND lesson_name IS NULL THEN '×'
													WHEN ls.lesson_type_id = 0 AND lesson_name IS NOT NULL THEN '予約済'
													WHEN ls.lesson_type_id <> 0 AND lesson_name IS NULL THEN '公開中'	
													WHEN ls.lesson_type_id <> 0 AND lesson_name IS NOT NULL THEN '予約済'
													ELSE ''
									END
							 ) as lesson_name
               ,COALESCE(ls.lesson_type_id,0) AS lesson_type_id
               ,COALESCE(ls.lesson_text_id,0) AS lesson_text_id
							 ,c.course_type as course_type
             FROM
               lesson_schedule ls
               LEFT OUTER JOIN lesson      le ON le.lesson_id = ls.lesson_id
               LEFT OUTER JOIN lesson_text lx ON lx.lesson_text_id = ls.lesson_text_id
							 LEFT JOIN course c ON c.course_id = ls.course_id
							 LEFT JOIN teacher t ON t.teacher_id = _teacher_id
							 LEFT JOIN timezone tz ON tz.timezone_id = t.timezone_id
             WHERE
               ls.teacher_id = _teacher_id
               AND DATE_FORMAT(lesson_date,'%Y%m%d') BETWEEN _d AND DATE_FORMAT(DATE_ADD(_d,INTERVAL 6 DAY),'%Y%m%d')
        
        ;
        END
        ";

        DB::unprepared($procedure1);

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
                   ,IF (ls.lesson_type_id = 0,'-', 'registered' ) AS lesson_name
									 ,COALESCE(lesson_name,'') as lesson_name_selected
                   ,COALESCE(ls.lesson_type_id,0) AS lesson_type_id
                   ,COALESCE(ls.lesson_text_id,0) AS lesson_text_id
                   ,COALESCE(ls.lesson_subscription_type,0) AS lesson_subscription_type
									 ,s.student_id as student_id
									 ,s.student_name as student_name
									 ,s.student_nickname as student_nickname
									 ,ls.course_type as course_type
                 FROM
                   lesson_schedule ls
                   LEFT OUTER JOIN lesson      le ON le.lesson_id = ls.lesson_id
                   LEFT OUTER JOIN lesson_text lx ON lx.lesson_text_id = ls.lesson_text_id
                    LEFT JOIN student_point_history sph ON ls.lesson_schedule_id = sph.lesson_schedule_id
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
