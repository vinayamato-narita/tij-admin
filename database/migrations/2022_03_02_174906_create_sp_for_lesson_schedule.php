<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpForLessonSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "
        DROP PROCEDURE IF EXISTS `sp_admin_register_lesson_for_teacher`;
        CREATE PROCEDURE `sp_admin_register_lesson_for_teacher`(
            IN _schedule_id INT,
            IN _teacher_id INT,
            IN _start_time VARCHAR(255),
            IN _end_time VARCHAR(255))
        BEGIN
        
        SET @schedule_id = 
        (SELECT COALESCE(lesson_schedule_id, 0) FROM lesson_schedule 
        WHERE teacher_id = _teacher_id AND lesson_starttime = _start_time);
        
        IF @schedule_id IS NOT NULL THEN
                
            UPDATE lesson_schedule
             SET
             lesson_type_id = 1
            ,lesson_subscription_type = 1
            ,last_update_date =  NOW()
             WHERE lesson_schedule_id = @schedule_id
             ;
             SELECT 1 as result;
        
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
                 DATE_FORMAT(_start_time,'%Y/%m/%d')
                 ,_start_time
                 ,_end_time
                 ,0
                 ,0
                 ,_teacher_id
                 ,1
                 ,0
                 ,1
                 ,1
                 , NOW()
               );
               
            
            SELECT 1 as result;
        END IF;
        END";

        \DB::unprepared($procedure1);

        $procedure2 = "
        DROP PROCEDURE IF EXISTS `sp_admin_remove_lesson_for_teacher`;
        CREATE PROCEDURE `sp_admin_remove_lesson_for_teacher`(
            IN _schedule_id INT
            )
            BEGIN
            SELECT lesson_id, lesson_starttime  INTO @lesson_id, @lesson_starttime 
              FROM lesson_schedule ls
             WHERE lesson_schedule_id =_schedule_id LIMIT 1;
             
            CASE WHEN @lesson_id <> 0 THEN 
            SELECT 0 AS result;
            ELSE
              UPDATE lesson_schedule
               SET
                     lesson_type_id = 0
                ,lesson_subscription_type = 0
                ,last_update_date =  NOW()
               WHERE lesson_schedule_id = _schedule_id
               ;
               SELECT 1 AS result;
            END CASE;
            END";

        \DB::unprepared($procedure2);

        $procedure3 = "
        DROP PROCEDURE IF EXISTS `sp_admin_get_lesson_schedule_list`;
        CREATE PROCEDURE `sp_admin_get_lesson_schedule_list`(
            IN _teacher_id int,
            IN _d char(8)
            )
            BEGIN
            SELECT
                   lesson_starttime as 'lesson_starttime'
                   ,CONCAT(DATE_FORMAT(lesson_starttime,'%H:%i'),'-',DATE_FORMAT(lesson_endtime,'%H:%i')) as 'lesson_time'
                   ,COALESCE(ls.lesson_schedule_id,0) AS lesson_schedule_id
                   ,COALESCE(ls.lesson_id,0) AS lesson_id
                   ,CONCAT(IF (ls.lesson_type_id = 0,'-', 'registered' ), '(',COALESCE(lesson_name,'-'),')') AS lesson_name
                   ,COALESCE(ls.lesson_type_id,0) AS lesson_type_id
                   ,COALESCE(ls.lesson_text_id,0) AS lesson_text_id
                   ,COALESCE(ls.lesson_subscription_type,0) AS lesson_subscription_type
                    
                 FROM
                   lesson_schedule ls
                   LEFT OUTER JOIN lesson      le ON le.lesson_id = ls.lesson_id
                   LEFT OUTER JOIN lesson_text lx ON lx.lesson_text_id = ls.lesson_text_id
                 WHERE
                   ls.teacher_id = _teacher_id
                   AND DATE_FORMAT(lesson_date,'%Y%m%d') BETWEEN _d AND DATE_FORMAT(DATE_ADD(_d,INTERVAL 6 day),'%Y%m%d')
            
            ;
            END";

        \DB::unprepared($procedure3);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sp_for_lesson_schedule');
    }
}
