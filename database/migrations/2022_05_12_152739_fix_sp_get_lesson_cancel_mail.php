<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSpGetLessonCancelMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_lesson_cancel_mail`;
        CREATE PROCEDURE `sp_get_lesson_cancel_mail`(IN _lesson_schedule_id INT,
        IN _student_id INT,
        IN _lang_type VARCHAR(10))
        BEGIN
            SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM timezone tz WHERE tz.timezone_id = 1 LIMIT 1),9 * 60);
            SET @timezone_id = COALESCE((SELECT timezone_id FROM student WHERE student_id = _student_id),1);
            SET @time_diff_to = COALESCE((SELECT diff_time * 60 FROM timezone WHERE timezone_id = @timezone_id),9 * 60);

            SELECT
                DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y-%m-%d') AS lesson_date
                ,DATE_FORMAT(lesson_starttime,'%Y-%m-%d') AS lesson_date_for_teacher
                ,CONCAT(
                    DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%H:%i')
                    ,'~',
                    DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_endtime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%H:%i')) AS lesson_time
                ,CONCAT(
                    DATE_FORMAT(lesson_starttime,'%H:%i')
                    ,'~',
                    DATE_FORMAT(lesson_endtime,'%H:%i')) AS lesson_time_for_teacher
                ,COALESCE(le.lesson_name,'') AS lesson_name
                ,COALESCE(lei.lesson_name, le.lesson_name, '') AS lesson_name_for_student
                ,COALESCE(lt.lesson_text_name,'') AS lesson_text_name
                ,COALESCE(lti.lesson_text_name, lt.lesson_text_name, '') AS lesson_text_name_for_student
                ,te.teacher_name
                ,te.teacher_nickname
                ,COALESCE(ti.teacher_name, te.teacher_name) AS   teacher_name_for_student
                ,COALESCE(ti.teacher_nickname, te.teacher_nickname) AS   teacher_nickname_for_student
                ,teacher_email
                ,teacher_skypename
                ,(SELECT student_name FROM student WHERE student_id = _student_id) AS student_name
                ,(SELECT student_email FROM student WHERE student_id = _student_id) AS student_email
                ,(SELECT student_nickname FROM student WHERE student_id = _student_id) AS student_nickname
                ,(SELECT student_skypename FROM student WHERE student_id = _student_id) AS student_skypename
            FROM
                lesson_schedule ls
            LEFT JOIN lesson le ON le.lesson_id = ls.lesson_id
            LEFT JOIN lesson_info lei ON lei.lesson_id = le.lesson_id AND lei.lang_type = _lang_type
            LEFT JOIN lesson_text_lesson ltl ON le.lesson_id = ltl.lesson_id
            LEFT JOIN lesson_text lt ON lt.lesson_text_id = ltl.lesson_text_id
            LEFT JOIN lesson_text_info lti ON lti.lesson_text_id = lt.lesson_text_id AND lti.lang_type = _lang_type
            LEFT JOIN teacher te ON te.teacher_id = ls.teacher_id
            LEFT JOIN teacher_info ti ON ti.teacher_id = te.teacher_id AND ti.lang_type = _lang_type
            WHERE
                lesson_schedule_id = _lesson_schedule_id
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
        Schema::dropIfExists('sp_get_lesson_cancel_mail');
    }
}
