<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForCheckLessonReservePointCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_check_lesson_reserve_point_count`;
        CREATE PROCEDURE `sp_check_lesson_reserve_point_count`(IN _student_id INT,
        IN _lesson_schedule_id INT,
        IN _course_id INT,
        OUT _result INT)
        BEGIN
            SELECT
                COALESCE((
                    SELECT
                        CASE
                            WHEN SUM(point_count) - 1 >= 0 THEN 1
                            ELSE 0
                        END point_result
                    FROM
                        student_point_history
                    WHERE
                        student_id = _student_id
                        AND course_id = _course_id

                        AND DATE(expire_date) >= DATE(DATE_ADD( NOW(), INTERVAL 90 MINUTE))
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
        Schema::dropIfExists('sp_check_lesson_reserve_point_count');
    }
}
