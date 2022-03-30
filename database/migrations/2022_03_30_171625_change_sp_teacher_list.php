<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeSpTeacherList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_teacher_list_teacher_search`;
        CREATE PROCEDURE `sp_get_teacher_list_teacher_search`(
            IN _date_time VARCHAR(255),
            IN _course_id VARCHAR(255),
            IN _lesson_id VARCHAR(255),
            IN _gender VARCHAR(255),
            IN _teacher_feature VARCHAR(255),
            IN _teacher_age VARCHAR(255),
            IN _lang_type VARCHAR(19),
            IN _search_input VARCHAR(255))
            BEGIN
                 SELECT
                        te.teacher_id
                        ,COALESCE(ti.teacher_nickname, te.teacher_nickname) AS teacher_nickname
                        ,te.photo_savepath AS photo_savepath
                        ,te.sound_savepath AS sound_savepath
                        ,te.movie_savepath AS movie_savepath
                        ,te.teacher_birthday AS teacher_birthday
                        ,te.teacher_sex AS teacher_sex
                        ,COALESCE(ti.teacher_introduction, te.teacher_introduction) AS teacher_introduction
                        ,COALESCE(ti.teacher_university, te.teacher_university) AS teacher_university
                        ,COALESCE(ti.teacher_department, te.teacher_department) AS teacher_department
                        ,te.teacher_hobby AS teacher_hobby
                        ,COALESCE(ti.introduce_from_admin, te.introduce_from_admin) AS introduce_from_admin
                        ,te.teacher_note
                 FROM
                 teacher te
                 LEFT JOIN teacher_info ti on ti.teacher_id = te.teacher_id and ti.lang_type = _lang_type
                 WHERE
                    te.show_flag = 1
                            AND
                     CASE
                        WHEN _gender = '' THEN
                            1 = 1
                        ELSE
                            FIND_IN_SET(te.teacher_sex,_gender)
                        END
                        AND EXISTS (
                        SELECT * FROM lesson_schedule ls WHERE ls.teacher_id = te.teacher_id
                        AND CASE
                                        WHEN _date_time = '' THEN
                                            1 = 1
                                        ELSE
                                            ls.lesson_schedule_id IN (SELECT lesson_schedule_id
                                            FROM lesson_schedule
                                            WHERE lesson_starttime <= _date_time
                                            AND lesson_endtime >= _date_time)
                                        END)
                         AND EXISTS (
                       SELECT * FROM teacher_lesson tl JOIN lesson l ON tl.lesson_id = l.lesson_id  WHERE tl.teacher_id = te.teacher_id
                       AND CASE
                                     WHEN _lesson_id = -1 THEN
                                            tl.lesson_id = l.lesson_id
                                     ELSE
                                            tl.lesson_id IN (SELECT lesson_id
                                        FROM lesson
                                        WHERE lesson_id LIKE CONCAT('%', _lesson_id, '%' ))
                                     END
                            AND EXISTS (
                       SELECT *
                                 FROM teacher_lesson tl
                                 JOIN lesson l ON tl.lesson_id = l.lesson_id
                                 JOIN course_lesson cl ON cl.lesson_id = l.lesson_id
                                 WHERE tl.teacher_id = te.teacher_id
                       AND CASE
                                     WHEN _course_id = -1 THEN
                                            1 = 1
                                     ELSE
                                            cl.course_id IN (SELECT course_id
                                        FROM course
                                        WHERE course_id LIKE CONCAT('%', _course_id, '%' ))
                                    END)
                            AND
                                 CASE
                                        WHEN _search_input = '' THEN
                                                1 = 1
                                        ELSE
                                                te.teacher_name LIKE CONCAT('%',_search_input, '%')
                                                AND te.teacher_nickname LIKE CONCAT('%',_search_input, '%')
                                                AND te.teacher_introduction LIKE CONCAT('%',_search_input, '%')
                                        END
                            AND
                                 CASE
                                        WHEN _teacher_age = '' THEN
                                                1 = 1
                                        WHEN _teacher_age = 1 THEN
                                                DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') >= 20
                                                AND DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') <= 29
                                        WHEN _teacher_age = 2 THEN
                                                DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') >= 30
                                                AND DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') <= 39
                                        WHEN _teacher_age = 3 THEN
                                                DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') >= 40
                                                AND DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') <= 49
                                        WHEN _teacher_age = 4 THEN
                                                DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') >= 50
                                                AND DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') <= 59
                                        WHEN _teacher_age = 5 THEN
                                                DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') >= 60
                                                AND DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') <= 69
                                        WHEN _teacher_age = 6 THEN
                                                DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') >= 70
                                                AND DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') <= 79
                                        WHEN _teacher_age = 7 THEN
                                                DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(te.teacher_birthday, '%Y') >= 80
                                        END
                            AND
                                 CASE
                                    WHEN _teacher_feature = '' THEN
                            1 = 1
                        ELSE
                                            FIND_IN_SET(te.teacher_feature,_teacher_feature)
                        END
                     )
                ORDER BY te.display_order ASC ,COALESCE(ti.teacher_nickname, te.teacher_nickname) ASC
                ;
            END
        ";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sp_teacher_list');
    }
}
