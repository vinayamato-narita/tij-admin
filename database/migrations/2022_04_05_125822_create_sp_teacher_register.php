<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpTeacherRegister extends Migration
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
        CREATE PROCEDURE `sp_get_lesson_schedule_list`(
            IN _teacher_id int,
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
                                WHEN ls.lesson_type_id = 0 AND lesson_name IS NOT NULL THEN '○'
                                WHEN ls.lesson_type_id <> 0 AND lesson_name IS NULL THEN '公開中'	
                                WHEN ls.lesson_type_id <> 0 AND lesson_name IS NOT NULL THEN CONCAT('予約済(',lesson_name,')')
                                ELSE ''
                        END
                     ) as lesson_name
               ,COALESCE(ls.lesson_type_id,0) AS lesson_type_id
               ,COALESCE(ls.lesson_text_id,0) AS lesson_text_id
                
             FROM
               lesson_schedule ls
               LEFT OUTER JOIN lesson      le ON le.lesson_id = ls.lesson_id
               LEFT OUTER JOIN lesson_text lx ON lx.lesson_text_id = ls.lesson_text_id
             WHERE
               ls.teacher_id = _teacher_id
               AND DATE_FORMAT(lesson_date,'%Y%m%d') BETWEEN _d AND DATE_FORMAT(DATE_ADD(_d,INTERVAL 6 DAY),'%Y%m%d')
        
        ;
        END";

        \DB::unprepared($procedure1);

        $procedure2 = "
        DROP PROCEDURE IF EXISTS `sp_teacher_get_free_lesson_setting`;
        CREATE PROCEDURE `sp_teacher_get_free_lesson_setting`(
            IN _d char(8))
        BEGIN
        
        SELECT
               lesson_starttime AS 'lesson_starttime'
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
             WHERE DATE_FORMAT(lesson_starttime,'%Y%m%d') BETWEEN _d AND DATE_FORMAT(DATE_ADD(_d,INTERVAL 6 DAY),'%Y%m%d')
               
             HAVING is_register_enable = 1
        ;
        END";

        \DB::unprepared($procedure2);

        $procedure3 = "
        DROP PROCEDURE IF EXISTS `sp_register_lesson_free_teacher`;
        CREATE PROCEDURE `sp_register_lesson_free_teacher`(
            IN _teacher_id INT,
            IN _start_time VARCHAR(255),
            IN _end_time VARCHAR(255))
        BEGIN
        
        
        SET @schedule_id = 
        (SELECT COALESCE(lesson_schedule_id, 0) FROM lesson_schedule 
        WHERE teacher_id = _teacher_id AND lesson_starttime = _start_time);
        
        SELECT COALESCE(is_free_teacher, 0) INTO @is_free_teacher 
          FROM teacher te WHERE te.teacher_id = _teacher_id LIMIT 1;
         
        CASE WHEN @is_free_teacher = 0 THEN select 0; ELSE
          SET @remain_lesson = 
                 (SELECT COALESCE (max_free_lesson, 0) FROM free_teacher_lesson_setting ftls WHERE ftls.lesson_starttime = _start_time LIMIT 1)
             - COALESCE(
                (SELECT COUNT(IF(te1.is_free_teacher = 1 AND ls1.lesson_type_id = 1 , ls1.lesson_schedule_id, NULL)) 
                FROM lesson_schedule ls1 LEFT JOIN teacher te1 ON te1.teacher_id = ls1.teacher_id
                WHERE ls1.lesson_starttime = _start_time
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
           END IF;
           
           SELECT 1 as result;
         ELSE
           SELECT 0;
        END CASE;
        END";

        \DB::unprepared($procedure3);

        $procedure4 = "
        DROP PROCEDURE IF EXISTS `sp_remove_lesson_free_teacher`;
        CREATE PROCEDURE `sp_remove_lesson_free_teacher`(
            IN _schedule_id INT,
            IN _brand_id INT
            )
            BEGIN
            SELECT lesson_id, lesson_starttime ,COALESCE(is_free_teacher, 0) INTO @lesson_id, @lesson_starttime, @is_free_teacher 
              FROM lesson_schedule ls LEFT JOIN teacher te ON te.teacher_id = ls.teacher_id
             WHERE lesson_schedule_id =_schedule_id  and te.brand_id = _brand_id LIMIT 1;
             
             SET @settingTime = (SELECT free_teacher_lesson_cancel_time FROM system_setting LIMIT 1);
             
             SET @canRemove = IF (@lesson_starttime > DATE_ADD( NOW(), INTERVAL @settingTime MINUTE), 1,0);
            CASE WHEN @is_free_teacher = 0 OR @canRemove = 0 OR @lesson_id <> 0 THEN 
            SELECT 0 AS result,
                  @settingTime,
                  @lesson_id,
                  @canRemove
                  ; 
            ELSE
              UPDATE lesson_schedule
               SET
                    lesson_type_id = 0
                ,lesson_subscription_type = 0
                ,last_update_date =  NOW()
               WHERE lesson_schedule_id = _schedule_id
               ;
               SELECT 1 AS result,
                  @settingTime,
                  @lesson_id,
                  @canRemove;
            END CASE;
        END";

        \DB::unprepared($procedure4);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sp_teacher_register');
    }
}
