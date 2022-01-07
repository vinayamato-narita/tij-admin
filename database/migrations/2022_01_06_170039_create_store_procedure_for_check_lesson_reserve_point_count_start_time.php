<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForCheckLessonReservePointCountStartTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_check_lesson_reserve_point_count_start_time`;
        CREATE PROCEDURE `sp_check_lesson_reserve_point_count_start_time`(IN _student_id INT,
        IN _lesson_schedule_id INT,
        IN _course_id INT,
        OUT _result INT)
        BEGIN
          DECLARE _lesson_starttime DATETIME;

            SET _lesson_starttime =  NOW();

            SELECT lesson_starttime INTO _lesson_starttime FROM lesson_schedule WHERE lesson_schedule_id = _lesson_schedule_id;
            SELECT
                COALESCE((
                    SELECT
                        CASE
                            WHEN SUM(sph.point_count) - 1 >= 0 THEN 1
                            ELSE 0
                        END point_result
                    FROM
                        student_point_history sph
                        LEFT JOIN point_subscription_history psh on  sph.point_subscription_id = psh.point_subscription_history_id
                    WHERE
                        sph.student_id = _student_id
                        AND DATE(sph.expire_date) >= DATE(_lesson_starttime)
                        AND sph.course_id = _course_id
                        AND coalesce(psh.begin_date, sph.start_date) <= _lesson_starttime
                        AND DATE(sph.expire_date) >= DATE(DATE_ADD( NOW(), INTERVAL 90 MINUTE))
                    ),1)
            INTO _result
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
        Schema::dropIfExists('sp_check_lesson_reserve_point_count_start_time');
    }
}
