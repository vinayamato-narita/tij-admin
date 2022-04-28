<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSpRemoveLessonFreeTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_remove_lesson_free_teacher`;
        CREATE PROCEDURE `sp_remove_lesson_free_teacher`(IN _schedule_id INT)
        BEGIN
            SELECT lesson_id, lesson_starttime ,COALESCE(is_free_teacher, 0) INTO @lesson_id, @lesson_starttime, @is_free_teacher 
              FROM lesson_schedule ls LEFT JOIN teacher te ON te.teacher_id = ls.teacher_id
             WHERE lesson_schedule_id =_schedule_id LIMIT 1;
             
             SET @settingTime = (SELECT free_teacher_lesson_cancel_time FROM system_setting LIMIT 1);
             
             SET @canRemove = IF (@lesson_starttime > DATE_ADD( NOW(), INTERVAL @settingTime MINUTE), 1,0);
						 
            CASE WHEN @is_free_teacher = 0 OR @canRemove = 0 OR @lesson_id <> 0 THEN 
            SELECT 0 AS result,
                  @settingTime,
                  @lesson_id,
                  @canRemove
                  ; 
            ELSE
						select 123;
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
