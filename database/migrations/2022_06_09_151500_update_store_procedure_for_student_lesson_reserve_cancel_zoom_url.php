<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStoreProcedureForStudentLessonReserveCancelZoomUrl extends Migration
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
          UPDATE lesson_schedule
          SET
            lesson_reserve_type = 1
            ,lesson_id = 0
            ,course_id = 0
            ,lesson_text_id = 0
            ,lesson_text_name = NULL
          WHERE
            lesson_schedule_id = _lesson_schedule_id
          ;

            INSERT INTO lesson_cancel_history (
                `student_id`,
                `teacher_id`,
                `lesson_date`,
                `lesson_starttime`,
                `reserve_date`,
                `cancel_date`,
                `cancel_student_comment`,
                `cancel_admin_comment`,
                `cancel_teacher_comment`,
                `lesson_schedule_id`
            )
                SELECT
                    lesson_history.student_id,
                    lesson_schedule.teacher_id,
                    lesson_schedule.lesson_date,
                    lesson_schedule.lesson_starttime,
                    lesson_history.reserve_date,
                    NOW(),
                    '',
                    '',
                    '',
                    lesson_history.lesson_schedule_id
                FROM
                    lesson_history
                JOIN lesson_schedule
                    ON lesson_schedule.lesson_schedule_id = lesson_history.lesson_schedule_id
                WHERE
                    lesson_history.lesson_schedule_id = _lesson_schedule_id
                    AND
                    lesson_history.student_id = _student_id
            ;

         DELETE FROM lesson_history
         WHERE
           lesson_schedule_id = _lesson_schedule_id
           AND student_id = _student_id
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
