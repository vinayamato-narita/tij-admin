<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSpGetLessonScheduleSearchNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_lesson_schedule_search`;
        CREATE PROCEDURE `sp_get_lesson_schedule_search`(
            IN _date_from VARCHAR(255),
            IN _date_to VARCHAR(255),
            IN _course_id VARCHAR(255),
            IN _lesson_id VARCHAR(255),
            IN _gender VARCHAR(255),
            IN _teacher_feature1 VARCHAR(255),
            IN _teacher_feature2 VARCHAR(255),
            IN _teacher_feature3 VARCHAR(255),
            IN _teacher_feature4 VARCHAR(255),
            IN _teacher_age VARCHAR(255),
            IN _lang_type VARCHAR(19),
            IN _search_input VARCHAR(255) CHARSET utf8,
            IN _favorite VARCHAR(255),
            IN _student_id VARCHAR(255))

            BEGIN
                 SELECT
                        te.teacher_id
                        ,COALESCE(ti.teacher_nickname, te.teacher_nickname) AS teacher_nickname
                        ,COALESCE(ti.teacher_name, te.teacher_name) AS teacher_name
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
                 LEFT JOIN teacher_info ti ON ti.teacher_id = te.teacher_id AND ti.lang_type = _lang_type
                 WHERE
                    CASE
                        WHEN _favorite = 1 THEN
                            te.teacher_id IN (
                                SELECT teacher_id FROM teacher_bookmark tb WHERE tb.student_id = _student_id
                            )
                        ELSE 1 = 1
                    END

                    AND
                        te.show_flag = 1
                    AND
                        CASE
                            WHEN _gender = '' THEN
                                1 = 1
                            ELSE
                                FIND_IN_SET(te.teacher_sex,_gender)
                        END
                    AND CASE
                        WHEN _date_from = '' OR _date_to = '' THEN
                            1 = 1
                        ELSE
                            EXISTS (
                                SELECT * FROM lesson_schedule ls WHERE ls.teacher_id = te.teacher_id
                                    AND ls.lesson_starttime >= _date_from
                                    AND ls.lesson_endtime <= _date_to
                            )
                    END
                    AND CASE
                        WHEN _lesson_id = -1 THEN
                            1 = 1
                        ELSE
                            EXISTS (
                                SELECT * FROM teacher_lesson tl JOIN lesson l ON tl.lesson_id = l.lesson_id  WHERE tl.teacher_id = te.teacher_id
                                    AND tl.lesson_id IN (SELECT lesson_id
                                        FROM lesson
                                        WHERE lesson_id LIKE CONCAT('%', _lesson_id, '%' ))
                            )
                    END
                    AND CASE
                            WHEN _course_id = -1 THEN
                                1 = 1
                            ELSE
                                EXISTS (
                                    SELECT *
                                    FROM teacher_lesson tl
                                    JOIN lesson l ON tl.lesson_id = l.lesson_id
                                    JOIN course_lesson cl ON cl.lesson_id = l.lesson_id
                                    WHERE tl.teacher_id = te.teacher_id
                                    AND cl.course_id IN (SELECT course_id
                                        FROM course
                                        WHERE course_id LIKE CONCAT('%', _course_id, '%' ))
                                )
                        END
                    AND
                        CASE
                            WHEN _search_input = '' THEN
                                    1 = 1
                            ELSE
                                ( te.teacher_nickname LIKE CONCAT('%',_search_input, '%')
                                OR te.teacher_introduction LIKE CONCAT('%',_search_input, '%'))
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
                            WHEN _teacher_feature1 = 1 THEN
                                te.teacher_feature1 = _teacher_feature1
                            ELSE
                                1 = 1
                        END
                    AND
                        CASE
                            WHEN _teacher_feature2 = 1 THEN
                                te.teacher_feature2 = _teacher_feature2
                            ELSE
                                1 = 1
                        END
                    AND
                        CASE
                            WHEN _teacher_feature3 = 1 THEN
                                te.teacher_feature3 = _teacher_feature3
                            ELSE
                                1 = 1
                        END
                    AND
                        CASE
                            WHEN _teacher_feature4 = 1 THEN
                                te.teacher_feature4 = _teacher_feature4
                            ELSE
                                1 = 1
                        END
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
        //
    }
}
