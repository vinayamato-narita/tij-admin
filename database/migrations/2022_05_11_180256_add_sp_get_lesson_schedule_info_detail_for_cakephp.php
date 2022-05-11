<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpGetLessonScheduleInfoDetailForCakephp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_lesson_schedule_info_detail_for_cakephp`;
        CREATE PROCEDURE `sp_get_lesson_schedule_info_detail_for_cakephp`(IN _lstime VARCHAR(50))
        BEGIN
        SELECT
                DATE_FORMAT(lesson_starttime,'%Y-%m-%d') AS lesson_date
            ,CONCAT(DATE_FORMAT(lesson_starttime,'%H:%i'),'-',DATE_FORMAT(lesson_endtime,'%H:%i')) AS 'lesson_time'
            ,ls.lesson_schedule_id       
            ,COALESCE(l.lesson_id,0) AS lesson_id
            ,COALESCE(l.lesson_name, '-') AS lesson_name
            ,COALESCE(ls.lesson_text_id, 0) AS lesson_text_id
            ,COALESCE(ltxt.lesson_text_name, '-') AS lesson_text_name
            ,COALESCE(lh.lesson_history_id,0) AS lesson_history_id
            ,COALESCE(lh.comment_from_student_to_teacher,'-') AS comment_from_student_to_teacher
            ,COALESCE(lh.comment_from_teacher_to_student,'-') AS comment_from_teacher_to_student
                ,COALESCE(IF(lh.teacher_rating = 0,'-',
                    ROUND((lh.teacher_rating + lh.teacher_attitude + lh.teacher_punctual + lh.skype_voice_rating_from_student)/4,2)),'-')AS teacher_rating
            
            ,COALESCE(lh.student_rating, '-') AS student_rating
            
            ,COALESCE(lh.student_id,0) AS student_id
            ,COALESCE(s.student_name,'-') AS student_name
            ,COALESCE(ls.teacher_id, 0) AS teacher_id
            ,COALESCE(t.teacher_name,'-') AS teacher_name
            ,COALESCE(s.student_nickname,'-') AS student_nickname
            ,COALESCE(s.student_skypename,'-') AS student_skype_name
            ,COALESCE(lh.reserve_date, '-') AS student_book_time
            ,t.is_free_teacher
        FROM lesson_schedule ls
        LEFT JOIN lesson l ON l.lesson_id = ls.lesson_id
        LEFT JOIN lesson_history lh ON (lh.lesson_schedule_id = ls.lesson_schedule_id AND lh.student_lesson_reserve_type <> 2)
        LEFT JOIN teacher t ON ls.teacher_id = t.teacher_id 
        LEFT JOIN student s ON lh.student_id = s.student_id
        LEFT JOIN lesson_text ltxt ON ltxt.lesson_text_id = ls.lesson_text_id
        WHERE DATE_FORMAT(lesson_starttime,'%Y-%m-%d %H:%i:%s') = _lstime
        AND ls.lesson_type_id <> 0
        ;
        END";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
