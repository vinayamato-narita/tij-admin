<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSpForLessonReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_student_lesson_history_by_schedule_id`;
        
        CREATE PROCEDURE `sp_get_student_lesson_history_by_schedule_id`(IN _student_id INT,
        IN _lesson_schedule_id BIGINT,
        IN _lang_type varchar(10))
BEGIN

            SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM  timezone tz WHERE tz.timezone_id = 1 LIMIT 1),9 * 60);
            SELECT
                lh.lesson_history_id,
                lh.lesson_schedule_id,
                lh.student_id,
                lh.course_id,
                ls.teacher_id,
                tchr.teacher_nickname,
                COALESCE(tchi.teacher_nickname, tchr.teacher_nickname) as teacher_nickname,
                tchr.teacher_skypename,
                tchr.teacher_email,
                tchr.photo_savepath,
                lsn.lesson_id,
                lsn.lesson_name,
                COALESCE(li.lesson_name, lsn.lesson_name) as lesson_name,
                lst.lesson_text_id,
                lst.lesson_text_url,
                COALESCE(COALESCE(lsti.lesson_text_name,lst.lesson_text_name), '') AS lesson_text_name,
                (SELECT COUNT(*) FROM teacher_bookmark WHERE student_id = _student_id AND teacher_id = ls.teacher_id) AS is_bookmarked,
                DATE_FORMAT(DATE_ADD(DATE_ADD(ls.lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE),'%Y-%m-%d') AS lesson_date,
                CONCAT(
                    DATE_FORMAT(DATE_ADD(DATE_ADD(ls.lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE),'%H:%i')
                    ,'~',
                    DATE_FORMAT(DATE_ADD(DATE_ADD(ls.lesson_endtime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE),'%H:%i')) AS lesson_time,
                DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE) AS lesson_starttime,
                DATE_ADD(DATE_ADD(lesson_endtime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE) AS lesson_endtime,
                comment_from_student_to_teacher
                ,(CASE WHEN accept_comment_to_student = 1 THEN comment_from_teacher_to_student ELSE '-' END) AS comment_from_teacher_to_student,
                comment_from_admin_to_student,
                comment_from_admin_to_teacher,
                note_from_student_to_teacher,
                teacher_rating,
                student_rating,
                skype_voice_rating_from_student,
                skype_voice_rating_from_teacher,
                note_from_teacher_to_student,
                lh.student_lesson_reserve_type
                ,ls.is_lesson_end
                ,lst.lesson_text_url
                ,lst.lesson_text_sound_url
                ,teacher_attitude
                ,teacher_punctual
                ,comment_from_student_to_office
                ,lh.reserve_date
                ,lsn.is_test_lesson
                ,lh.marks
								,c.course_type
								,COALESCE(ci.course_name, c.course_name) as course_name
								,tchr.teacher_name
            FROM
                lesson_history lh
                LEFT JOIN lesson_schedule ls ON lh.lesson_schedule_id = ls.lesson_schedule_id
                LEFT JOIN teacher tchr ON ls.teacher_id = tchr.teacher_id
                LEFT JOIN teacher_info tchi on tchi.teacher_id = ls.teacher_id and tchi.lang_type = _lang_type
                LEFT JOIN lesson lsn ON ls.lesson_id = lsn.lesson_id
                LEFT JOIN lesson_info li on li.lesson_id = lsn.lesson_id and li.lang_type = _lang_type
                LEFT JOIN lesson_text lst ON ls.lesson_text_id = lst.lesson_text_id
                        LEFT JOIN lesson_text_info lsti ON lsti.lesson_text_id = lst.lesson_text_id AND li.lang_type = _lang_type
                LEFT JOIN student st ON st.student_id = lh.student_id
                LEFT JOIN timezone tz ON tz.timezone_id = st.timezone_id
								LEFT JOIN course c ON lh.course_id = c.course_id
								LEFT JOIN course_info ci on ci.course_id = c.course_id and ci.lang_type = _lang_type
            WHERE
                lh.student_id = _student_id
                AND ls.lesson_schedule_id = _lesson_schedule_id
                ;
        END
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
        //
    }
}
