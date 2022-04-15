<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSpTeacherLessonHistoryListForTopHtml extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "
        DROP PROCEDURE IF EXISTS `sp_teacher_lesson_history_list_for_top_html`;
        CREATE PROCEDURE `sp_teacher_lesson_history_list_for_top_html`(
            IN _teacher_id int,
            IN _dateTo varchar(50)
            )
        BEGIN
            SELECT
                ls.lesson_schedule_id,
                ls.lesson_endtime,
                lsn.lesson_name,
                lst.lesson_text_name,
                DATE_FORMAT(ls.lesson_date,'%Y/%m/%d') as lesson_date,
                ls.lesson_starttime,
                CONCAT(DATE_FORMAT(ls.lesson_starttime,'%H:%i'),'~',DATE_FORMAT(ls.lesson_endtime,'%H:%i')) as lesson_time,
                COALESCE(s.student_nickname,'-') as student_nickname,
                COALESCE(s.student_skypename,'-') as student_skypename,
                lst.lesson_text_id,
                lst.lesson_text_url,
                lst.lesson_text_url_for_teacher,
                CASE
                    WHEN ls.is_lesson_end = 1 THEN 'After Lesson'
                    ELSE 'Before Lesson'
                END lesson_status_name,
                s.student_id,
                ls.teacher_id,
                IF(DATE_FORMAT(ls.lesson_starttime, '%Y-%m-%d %H:%i:%s') <= DATE_FORMAT(DATE_ADD( NOW(), INTERVAL 30 minute), '%Y-%m-%d %H:%i:%s'), 1, 0) is_now,
                CASE (SELECT min(lesson_history_id) FROM lesson_history WHERE student_id = s.student_id )
                    WHEN lh.lesson_history_id THEN 'Free' 
                    ELSE ''
                END is_for_freestudent
                , ftc.file_path as teacher_file_path
                , fst.file_path as student_file_path
            FROM
                lesson_history lh
                LEFT JOIN lesson_schedule ls ON ls.lesson_schedule_id = lh.lesson_schedule_id
                LEFT JOIN student s ON lh.student_id = s.student_id
                LEFT JOIN lesson lsn ON ls.lesson_id = lsn.lesson_id
                LEFT JOIN lesson_text_lesson ltl ON lsn.lesson_id = ltl.lesson_id
                LEFT JOIN lesson_text lst ON ltl.lesson_text_id = lst.lesson_text_id
                LEFT JOIN file ftc ON lst.lesson_text_teacher_file_id = ftc.file_id
                LEFT JOIN file fst ON lst.lesson_text_student_file_id = fst.file_id
            WHERE           
                lh.student_lesson_reserve_type in (1,3)
                AND COALESCE(ls.lesson_schedule_id,0) <> 0
                AND ls.teacher_id = _teacher_id
                AND ls.lesson_endtime >= DATE_ADD( NOW(), INTERVAL -30 MINUTE)
                AND ls.lesson_date <= DATE_FORMAT(_dateTo,'%Y-%m-%d 23:59:59')
            ORDER BY
                lesson_starttime;
        END
        ";

        \DB::unprepared($procedure1);
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
