<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSpRegisterLessonFreeTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
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
					
				SELECT COALESCE(zoom_personal_meeting_id, null) INTO @zoom_personal_meeting_id 
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
                last_update_date,
								zoom_url
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
								 ,@zoom_personal_meeting_id
               );
           END IF;
           
           SELECT 1 as result;
         ELSE
           SELECT 0;
        END CASE;
        END";

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
