<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForLessonStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "
        DROP PROCEDURE IF EXISTS `sp_get_lesson_schedule_info_for_export_csv`;
        CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_lesson_schedule_info_for_export_csv`(IN _date_from VARCHAR(20),
IN _date_to VARCHAR(20))
BEGIN
   SELECT
        DATE_FORMAT(lesson_starttime,'%Y-%m-%d') AS lesson_date
       ,CONCAT(DATE_FORMAT(lesson_starttime,'%H:%i'),'-',DATE_FORMAT(lesson_endtime,'%H:%i')) AS 'lesson_time'
       ,ls.lesson_schedule_id
       ,COALESCE(l.lesson_id,0) AS lesson_id
       ,COALESCE(l.lesson_name, '') AS lesson_name
       ,COALESCE(ls.lesson_text_id, 0) AS lesson_text_id
       ,COALESCE(ltxt.lesson_text_name, '') AS lesson_text_name
       ,COALESCE(lh.lesson_history_id,0) AS lesson_history_id
       ,COALESCE(lh.comment_from_student_to_teacher,'') AS comment_from_student_to_teacher
       ,COALESCE(lh.comment_from_teacher_to_student,'') AS comment_from_teacher_to_student
        ,IF(lh.teacher_rating = 0,'-',
               ROUND((lh.teacher_rating + lh.teacher_attitude + lh.teacher_punctual + lh.skype_voice_rating_from_student)/4,2))AS teacher_rating
       ,IF(lh.student_rating  = 0 ,'-',lh.student_rating ) AS student_rating
       ,COALESCE(lh.student_id,'') AS student_id
       ,COALESCE(s.student_name,'') AS student_name
       ,COALESCE(ls.teacher_id, '') AS teacher_id
       ,COALESCE(t.teacher_name,'') AS teacher_name
       ,COALESCE(s.student_nickname,'') AS student_nickname
       ,COALESCE(s.student_skypename,'') AS student_skype_name
       ,lh.reserve_date AS student_book_time
FROM lesson_schedule ls
LEFT JOIN lesson l ON l.lesson_id = ls.lesson_id
LEFT JOIN lesson_history lh ON (lh.lesson_schedule_id = ls.lesson_schedule_id AND lh.student_lesson_reserve_type <> 2)
LEFT JOIN teacher t ON ls.teacher_id = t.teacher_id
LEFT JOIN student s ON lh.student_id = s.student_id
LEFT JOIN lesson_text ltxt ON ltxt.lesson_text_id = ls.lesson_text_id
WHERE DATE_FORMAT(lesson_starttime,'%Y-%m-%d') >= _date_from
AND DATE_FORMAT(lesson_starttime,'%Y-%m-%d') <= _date_to
AND ls.lesson_type_id =1 -- registerd
ORDER BY lesson_starttime
 ;
END
        ";

        \DB::unprepared($procedure1);

        $procedure2 = "
        DROP PROCEDURE IF EXISTS `sp_copy_setting_lesson_free`;
        CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_copy_setting_lesson_free`(
            IN _start_date VARCHAR(50)
           )
           BEGIN
            SET @end_date = DATE_ADD(_start_date, INTERVAL 7 DAY);
                   SET @prev_week_start = DATE_ADD(_start_date, INTERVAL -7 DAY);
                   SET @prev_week_end = _start_date;
           
                   DELETE FROM free_teacher_lesson_setting WHERE lesson_starttime >= _start_date AND lesson_starttime < @end_date;
           
                   INSERT INTO free_teacher_lesson_setting (lesson_starttime, max_free_lesson, created, modified)
                       (SELECT DATE_ADD(lesson_starttime, INTERVAL 7 DAY), max_free_lesson,  NOW(),  NOW()
                       FROM free_teacher_lesson_setting WHERE lesson_starttime >= @prev_week_start AND lesson_starttime < @prev_week_end);
           
           END
        ";

        \DB::unprepared($procedure2);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_procedure_for_lesson_status');
    }
}
