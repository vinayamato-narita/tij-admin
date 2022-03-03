<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForCheckLessonReserve extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_check_lesson_reserve`;
        CREATE PROCEDURE `sp_check_lesson_reserve`(IN _student_id INT,
        IN _lesson_schedule_id INT ,
        IN _course_id   INT)
        BEGIN
           SET @skypeName = (SELECT coalesce(student_skypename, '') from student where student_id = _student_id limit 1);
           CASE @skypeName
               WHEN '' THEN SELECT 10; -- check skype empty
               ELSE
                 CALL sp_check_lesson_reserve_time(_student_id,_lesson_schedule_id,@rtn);
           END CASE;

           CASE @rtn
              WHEN 1 THEN SELECT 1;
              ELSE CALL sp_check_lesson_reserve_lesson_count(_student_id,_course_id, @rtn);
            END CASE;
           CASE @rtn
              WHEN 0 THEN SELECT 2;
              ELSE CALL sp_check_lesson_reserve_point_count(_student_id, _lesson_schedule_id, _course_id, @rtn);
            END CASE;


            CASE @rtn
              WHEN 0 THEN SELECT 3;
              ELSE CALL sp_check_lesson_reserve_point_count_start_time(_student_id, _lesson_schedule_id, _course_id, @rtn);
            END CASE;

            CASE @rtn
              WHEN 0 THEN SELECT 6;
              ELSE CALL sp_check_lesson_reserve_on_time(_lesson_schedule_id, _course_id, @rtn);
            END CASE;

            CASE @rtn
              WHEN 0 THEN SELECT 5;
              ELSE SELECT 0;
            END CASE;
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
        Schema::dropIfExists('sp_check_lesson_reserve');
    }
}
