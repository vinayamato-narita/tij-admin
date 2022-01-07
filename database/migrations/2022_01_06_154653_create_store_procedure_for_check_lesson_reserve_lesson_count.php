<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForCheckLessonReserveLessonCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_check_lesson_reserve_lesson_count`;
        CREATE PROCEDURE `sp_check_lesson_reserve_lesson_count`(IN _student_id int,
        IN _course_id int,
        OUT _result int)
        BEGIN

            SET @c_max = (select COALESCE(max_reserve_count,0) from course where course_id = _course_id);
            SET @limit_reserve_amount = @c_max;

            SELECT
            COALESCE((
                SELECT
                    CASE
                        WHEN COUNT(*) >= @limit_reserve_amount THEN '0'
                        ELSE '1'
                    END lesson_reserve_count
                FROM
                    lesson_history lh
                WHERE
                    lh.student_id = _student_id
                    AND student_lesson_reserve_type = 1
                    AND course_id = _course_id
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
        Schema::dropIfExists('sp_check_lesson_reserve_lesson_count');
    }
}
