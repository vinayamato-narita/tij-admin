<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForStudentLessonReserveRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_student_lesson_reserve_register`;
        CREATE PROCEDURE `sp_student_lesson_reserve_register`(IN _lesson_schedule_id int,
        IN _student_id int,
        IN _lesson_id int,
        IN _lesson_text_id int ,
        IN _course_id  int)
        BEGIN
           SET @student_id = _student_id;
           SET @lesson_count = (SELECT COUNT(*) FROM lesson_schedule WHERE lesson_schedule_id = _lesson_schedule_id AND lesson_reserve_type <> 2 AND is_lesson_end <> true);
           SET @point_brand = 1;
           SET @lesson_text_name = (SELECT SUBSTRING(lesson_text_name,1,45) FROM lesson_text WHERE lesson_text_id = _lesson_text_id);
        CASE
          WHEN @lesson_count <> 0 THEN
                SET @c = (SELECT count(*) FROM lesson_history WHERE student_id = _student_id AND lesson_schedule_id = _lesson_schedule_id);
                CASE @c
                  WHEN 0 THEN
                         INSERT INTO lesson_history (
                            lesson_schedule_id
                            ,student_id
                            ,comment_from_student_to_teacher
                            ,comment_from_teacher_to_student
                            ,comment_from_admin_to_student
                            ,comment_from_admin_to_teacher
                            ,note_from_student_to_teacher
                            ,teacher_rating
                            ,student_rating
                            ,student_lesson_reserve_type
                            ,reserve_date
                            ,course_id
                          ) VALUES (
                            _lesson_schedule_id
                            ,_student_id
                            ,''
                            ,''
                            ,''
                            ,''
                            ,''
                            ,0
                            ,0
                            ,1
                            , NOW()
                            ,_course_id
                          );
                  ELSE
                    UPDATE lesson_history
                    SET
                       student_lesson_reserve_type = 1
                       ,reserve_date =  NOW()
                       ,course_id = _course_id
                    WHERE
                      student_id = _student_id
                      AND lesson_schedule_id = _lesson_schedule_id
                    ;
                END CASE;
                SET @h = (SELECT
                            count(*)
                          FROM
                            student_point_history
                          WHERE
                            student_id = @student_id
                            AND lesson_schedule_id = _lesson_schedule_id
                          );
                CASE @h
                  WHEN 0 THEN
                     call sp_insert_student_point_history_by_lesson_reserve_register(_lesson_schedule_id,@student_id,_course_id);
                  ELSE
                    UPDATE student_point_history
                    SET
                      point_count = point_count - @point_brand

                      ,course_id = _course_id

                    WHERE
                      student_id = @student_id
                      AND lesson_schedule_id = _lesson_schedule_id
                    ;
                  END CASE;
                UPDATE lesson_schedule
                 SET
                   lesson_id = _lesson_id
                   ,course_id = _course_id
                   ,lesson_text_id = _lesson_text_id
                   ,lesson_text_name = @lesson_text_name
                   ,lesson_reserve_type = 2
                 WHERE
                   lesson_schedule_id = _lesson_schedule_id
                 ;
          ELSE
            select @lesson_count AS lesson_count;
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
        Schema::dropIfExists('sp_student_lesson_reserve_register');
    }
}
