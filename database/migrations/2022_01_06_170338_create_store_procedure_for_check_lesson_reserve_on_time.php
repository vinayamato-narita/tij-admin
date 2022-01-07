<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForCheckLessonReserveOnTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_check_lesson_reserve_on_time`;
        CREATE PROCEDURE `sp_check_lesson_reserve_on_time`(IN _lesson_schedule_id INT,
        IN _course_id INT,
        OUT _result INT)
        BEGIN
            SELECT COALESCE(
                (SELECT
                     1  AS wright
                FROM lesson_schedule ls
                INNER JOIN course c ON c.course_id = _course_id
                LEFT JOIN course_schedule_setting css ON c.course_id = css.course_id
                         AND INSTR (css.date_type, DATE_FORMAT(ls.lesson_date , '%w')) > 0
                         AND DATE_FORMAT(ls.lesson_starttime,'%H:%i') BETWEEN css.start_time AND css.end_time
                WHERE
                     ls.lesson_schedule_id = _lesson_schedule_id
                     AND css.id IS NOT NULL
                    AND CASE WHEN c.reserve_end_date IS NULL
                        THEN ls.lesson_starttime >= NOW()
                        ELSE c.reserve_end_date <= ls.lesson_starttime
                        END
                GROUP BY ls.lesson_schedule_id
                LIMIT 1
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
        Schema::dropIfExists('sp_check_lesson_reserve_on_time');
    }
}
