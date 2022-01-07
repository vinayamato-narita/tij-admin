<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForStudentLessonReserveCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_student_lesson_reserve_cancel`;
        CREATE PROCEDURE `sp_student_lesson_reserve_cancel`(IN _lesson_schedule_id int,
        IN _student_id int ,
        IN _course_id int)
        BEGIN
         SET @count_student = (SELECT COUNT(*) FROM lesson_history WHERE lesson_schedule_id = _lesson_schedule_id AND student_lesson_reserve_type <> 2);
         UPDATE lesson_history
         SET
           student_lesson_reserve_type = 2
         WHERE
           lesson_schedule_id = _lesson_schedule_id
           AND student_id = _student_id
          ;

          UPDATE lesson_schedule
          SET
            lesson_reserve_type = 1
            #,lesson_id = 0
            #,lesson_text_id = 0
            #,lesson_text_name = NULL
          WHERE
            lesson_schedule_id = _lesson_schedule_id
          ;

           DELETE FROM student_point_history
            WHERE
                lesson_schedule_id = _lesson_schedule_id
                AND student_id = _student_id
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
        Schema::dropIfExists('sp_student_lesson_reserve_cancel');
    }
}
