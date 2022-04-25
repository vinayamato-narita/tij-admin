<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpGetLessonScheduleList extends Migration
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
        
        SELECT
               lesson_starttime AS 'lesson_starttime'
               ,CONCAT(DATE_FORMAT(lesson_starttime,'%H:%i'),'-',DATE_FORMAT(lesson_endtime,'%H:%i')) AS 'lesson_time'
               ,COALESCE(ls.lesson_schedule_id,0) AS lesson_schedule_id
               ,COALESCE(ls.lesson_id,0) AS lesson_id
							 ,(CASE 
													WHEN ls.lesson_type_id = 0 AND lesson_name IS NULL THEN '×'
													WHEN ls.lesson_type_id = 0 AND lesson_name IS NOT NULL THEN CONCAT('予約済(',lesson_name,')')
													WHEN ls.lesson_type_id <> 0 AND lesson_name IS NULL THEN '公開中'	
													WHEN ls.lesson_type_id <> 0 AND lesson_name IS NOT NULL THEN CONCAT('予約済(',lesson_name,')')
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
             WHERE
               ls.teacher_id = _teacher_id
               AND DATE_FORMAT(lesson_date,'%Y%m%d') BETWEEN _d AND DATE_FORMAT(DATE_ADD(_d,INTERVAL 6 DAY),'%Y%m%d')
        
        ;
        END
        ";

        \DB::unprepared($procedure1);
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
