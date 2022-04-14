<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSpTeacherLessonHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_teacher_lesson_history_list_html`;
        CREATE PROCEDURE `sp_teacher_lesson_history_list_html`(
            IN _teacher_id INT,
            IN _dateFrom VARCHAR(10),
            IN _dateTo VARCHAR(10))
        BEGIN
            SELECT
                lh.lesson_history_id,
                lh.lesson_schedule_id,
                -- lhw.lesson_homework_id,
                -- lhw.status,
                -- lhw.homeworkvalue,
                lh.student_id,
                stdn.student_nickname,
                stdn.student_skypename,
                stdn.student_nickname,
                stdn.student_birthday,
                st.sex_type_name_en AS student_sex,
                stdn.photo_savepath,
                stdn.student_introduction,
                ls.teacher_id,
                tchr.teacher_name,
                c.course_name,
                c.course_id,
                c.course_type,
                lsn.lesson_id,
                lsn.lesson_name,
                lst.lesson_text_id,
                lst.lesson_text_name,
                lst.lesson_text_url,
                lst.lesson_text_url_for_teacher,
                ls.lesson_date,
                ls.lesson_starttime,
                ls.lesson_endtime,
                CASE accept_comment_to_teacher
                        WHEN  1 THEN comment_from_student_to_teacher 
                        ELSE '-' 
                END AS comment_from_student_to_teacher,
                comment_from_teacher_to_student,
                comment_from_admin_to_student,
                comment_from_admin_to_teacher,
                note_from_student_to_teacher,
                note_from_teacher_to_student,
                CASE accept_comment_to_teacher
                        WHEN 1 THEN teacher_rating
                        ELSE '-' 
                END AS teacher_rating,
                COALESCE(
                        (CASE accept_comment_to_teacher
                                WHEN 1 THEN skype_voice_rating_from_student 
                                ELSE '-' 
                        END)
                        ,0) AS skype_voice_rating_from_student,
                student_rating,
                COALESCE(skype_voice_rating_from_teacher,0),
                ls.lesson_reserve_type,
                CASE ls.is_lesson_end
                    WHEN 1 THEN 'After Lesson'
                    ELSE 'Before Lesson'
                END lesson_status_name,
                -- tlss.lesson_reserve_type_name_en as lesson_reserve_type_name,
                ls.is_lesson_end
                ,ls.lesson_endtime
                ,comment_from_teacher_to_office
                ,CASE accept_comment_to_teacher
                        WHEN 1 THEN teacher_attitude
                        ELSE '-'
                END AS teacher_attitude
                ,CASE accept_comment_to_teacher
                        WHEN 1 THEN teacher_punctual 
                        ELSE '-' 
                END AS teacher_punctual
                ,CASE
                        WHEN (teacher_attitude+teacher_punctual+skype_voice_rating_from_student+teacher_rating) >0 THEN (teacher_attitude+teacher_punctual+skype_voice_rating_from_student+teacher_rating)
                        ELSE ''
                END AS teacher_rating_sum
                ,CASE  (SELECT MIN(lesson_history_id) FROM lesson_history WHERE student_id = stdn.student_id AND student_lesson_reserve_type <> 2)
                            WHEN lh.lesson_history_id  THEN 'Free'
                        ELSE ''
                END is_for_freestudent
                ,CASE WHEN (SELECT MIN(lesson_history_id) FROM lesson_history WHERE student_id = stdn.student_id AND student_lesson_reserve_type <> 2)
                            = lh.lesson_history_id AND stdn.course_id > 1 THEN 'Success'
                        ELSE ''
                END user_status
                ,lsn.is_test_lesson
                ,lh.marks
            FROM
                lesson_history lh
                LEFT JOIN lesson_schedule ls ON lh.lesson_schedule_id = ls.lesson_schedule_id
                LEFT JOIN teacher tchr ON ls.teacher_id = tchr.teacher_id
                LEFT JOIN student stdn ON lh.student_id = stdn.student_id
                LEFT JOIN lesson lsn ON ls.lesson_id = lsn.lesson_id
                LEFT JOIN sex_type st ON st.sex_type = stdn.student_sex
                LEFT JOIN course c ON c.course_id = lh.course_id
                LEFT JOIN lesson_text_lesson ltl ON lsn.lesson_id = ltl.lesson_id
                LEFT JOIN lesson_text lst ON ltl.lesson_text_id = lst.lesson_text_id
            WHERE       
                ls.teacher_id = _teacher_id
                AND student_lesson_reserve_type IN (1,3,4,5)
                AND ls.lesson_date BETWEEN DATE_FORMAT(_dateFrom,'%Y-%m-%d 00:00:00') AND DATE_FORMAT(_dateTo,'%Y-%m-%d 23:59:59')
            ORDER BY 
                lesson_starttime DESC;
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
        Schema::dropIfExists('sp_teacher_lesson_history_list_html');
    }
}
