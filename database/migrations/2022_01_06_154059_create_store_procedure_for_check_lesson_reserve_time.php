<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForCheckLessonReserveTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_check_lesson_reserve_time`;
        CREATE PROCEDURE `sp_check_lesson_reserve_time`(IN _student_id int,
        IN _lesson_schedule_id int,
        OUT _result int)
        BEGIN
          SELECT COALESCE((SELECT
                            count(lesson_history_id) as lesson_count
                          FROM
                            lesson_history lh
                            LEFT JOIN lesson_schedule ls ON ls.lesson_schedule_id = lh.lesson_schedule_id
                          WHERE
                            ls.lesson_starttime = (SELECT lesson_starttime FROM lesson_schedule ls2 WHERE ls2.lesson_schedule_id = _lesson_schedule_id)
                            AND lh.lesson_schedule_id <> _lesson_schedule_id
                            AND lh.student_lesson_reserve_type <> 2
                            AND student_id = _student_id
                        ),0) INTO _result
                        ;
        END;
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
        Schema::dropIfExists('sp_check_lesson_reserve_time');
    }
}
