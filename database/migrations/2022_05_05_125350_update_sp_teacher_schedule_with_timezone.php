<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSpTeacherScheduleWithTimezone extends Migration
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
							 LEFT JOIN teacher t ON t.teacher_id = _teacher_id
							 LEFT JOIN timezone tz ON tz.timezone_id = t.timezone_id
             WHERE
               ls.teacher_id = _teacher_id
               AND DATE_FORMAT(lesson_date,'%Y%m%d') BETWEEN _d AND DATE_FORMAT(DATE_ADD(_d,INTERVAL 6 DAY),'%Y%m%d')
        
        ;
        END
        ";

        DB::unprepared($procedure1);

        $procedure2 = "
        DROP PROCEDURE IF EXISTS `sp_teacher_get_free_lesson_setting`;
        CREATE PROCEDURE `sp_teacher_get_free_lesson_setting`(IN _d char(8), IN _teacher_id INT)
BEGIN
        SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM  timezone tz WHERE tz.timezone_id = 1 LIMIT 1),9 * 60);
				
        SELECT
							 DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE) AS lesson_starttime
               ,IF ( lesson_starttime >  NOW() 
                   AND 
                   COALESCE (max_free_lesson, 0)
             - COALESCE(
                (SELECT COUNT(IF(te1.is_free_teacher = 1 AND ls1.lesson_type_id = 1, ls1.lesson_schedule_id, NULL)) 
                FROM lesson_schedule ls1 LEFT JOIN teacher te1 ON te1.teacher_id = ls1.teacher_id
                WHERE ls1.lesson_starttime = ftls.lesson_starttime
             ),0) > 0, 1,0 )
            is_register_enable
						
             FROM
               free_teacher_lesson_setting ftls
						 LEFT JOIN teacher t on t.teacher_id = _teacher_id
						 LEFT JOIN timezone tz on tz.timezone_id = t.timezone_id
             WHERE DATE_FORMAT(lesson_starttime,'%Y%m%d') BETWEEN _d AND DATE_FORMAT(DATE_ADD(_d,INTERVAL 6 DAY),'%Y%m%d')
               
             HAVING is_register_enable = 1
        ;
        END
        ";

        DB::unprepared($procedure2);

        $procedure3 = "
        DROP PROCEDURE IF EXISTS `sp_register_lesson_free_teacher`;
        CREATE PROCEDURE `sp_register_lesson_free_teacher`(IN _teacher_id INT,
            IN _start_time VARCHAR(255),
            IN _end_time VARCHAR(255))
BEGIN
				SET @diff_time = (SELECT COALESCE(tz.diff_time, 0) FROM teacher t LEFT JOIN timezone tz on tz.timezone_id = t.timezone_id WHERE t.teacher_id = _teacher_id);
        SET @time_diff_from = @diff_time * 60;
        SET @start_time = DATE_ADD(DATE_ADD(_start_time,INTERVAL -@time_diff_from MINUTE),INTERVAL 9 * 60 MINUTE);
				SET @end_time = DATE_ADD(DATE_ADD(_end_time,INTERVAL -@time_diff_from MINUTE),INTERVAL 9 * 60 MINUTE);
				
        SET @schedule_id = 
        (SELECT COALESCE(lesson_schedule_id, 0) FROM lesson_schedule 
        WHERE teacher_id = _teacher_id AND lesson_starttime = @start_time);
        
        SELECT COALESCE(is_free_teacher, 0) INTO @is_free_teacher 
          FROM teacher te WHERE te.teacher_id = _teacher_id LIMIT 1;
         
        CASE WHEN @is_free_teacher = 0 THEN select 0; ELSE
          SET @remain_lesson = 
                 (SELECT COALESCE (max_free_lesson, 0) FROM free_teacher_lesson_setting ftls WHERE ftls.lesson_starttime = @start_time LIMIT 1)
             - COALESCE(
                (SELECT COUNT(IF(te1.is_free_teacher = 1 AND ls1.lesson_type_id = 1 , ls1.lesson_schedule_id, NULL)) 
                FROM lesson_schedule ls1 LEFT JOIN teacher te1 ON te1.teacher_id = ls1.teacher_id
                WHERE ls1.lesson_starttime = @start_time
             ),0) ;
        END CASE;
				
        CASE WHEN @remain_lesson IS NOT NULL AND @remain_lesson > 0 THEN
           IF @schedule_id IS NOT NULL THEN
           UPDATE lesson_schedule
               SET
                 lesson_type_id = 1
                ,lesson_subscription_type = 1
                ,last_update_date =  NOW()
               WHERE lesson_schedule_id = @schedule_id
               ;
           ELSE
            
            INSERT INTO lesson_schedule
              (
                lesson_date,
                lesson_starttime,
                lesson_endtime,
                lesson_id,
                lesson_text_id,
                teacher_id,
                lesson_type_id,
                is_lesson_end,
                lesson_reserve_type,
                lesson_subscription_type,
                last_update_date
              ) VALUES (
                 DATE_FORMAT(@start_time,'%Y/%m/%d')
                 ,@start_time
                 ,@end_time
                 ,0
                 ,0
                 ,_teacher_id
                 ,1
                 ,0
                 ,1
                 ,1
                 , NOW()
               );
           END IF;
           
           SELECT 1 as result;
         ELSE
           SELECT 0;
        END CASE;
        END
        ";

        DB::unprepared($procedure3);
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
