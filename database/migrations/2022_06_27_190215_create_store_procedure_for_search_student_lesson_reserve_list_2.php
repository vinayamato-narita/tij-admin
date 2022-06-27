<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForSearchStudentLessonReserveList2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_search_student_lesson_reserve_list`;
        CREATE PROCEDURE `sp_search_student_lesson_reserve_list`(IN _course_id INT,
            IN _lesson_id INT,
            IN _lesson_reserve_date_from VARCHAR(255),
            IN _lesson_reserve_date_to VARCHAR(255),
            IN _lesson_reserve_time_from VARCHAR(255),
            IN _lesson_reserve_time_to VARCHAR(255),
            IN _teacher_id INT,
            IN _student_id INT,
            IN _time_to_booking INT)
        BEGIN
            SET @timezone_id = COALESCE((SELECT timezone_id FROM student WHERE student_id = _student_id),1);
            SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM timezone tz WHERE tz.timezone_id = 1 LIMIT 1),9 * 60);
            SET @time_diff_to = COALESCE((SELECT diff_time * 60 FROM timezone WHERE timezone_id = @timezone_id),9 * 60);

            IF _lesson_reserve_date_from = '' THEN
            SET _lesson_reserve_date_from = DATE_FORMAT(DATE_ADD(DATE_ADD(NOW() ,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y-%m-%d');
            END IF;

            IF _lesson_reserve_date_to = '' THEN
            SET _lesson_reserve_date_to = DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(NOW() ,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE), INTERVAL 2 WEEK),'%Y-%m-%d');
            END IF;

            SELECT
                DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y/%m/%d')  AS lesson_date
                ,CONCAT(DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%H:%i')) AS lesson_starttime
                ,CONCAT(DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_endtime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%H:%i')) AS lesson_endtime
                ,DATE_ADD(DATE_ADD(lesson_endtime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE) AS lesson_enddatetime
                ,lesson_schedule_id
                ,ls.lesson_type_id
                ,ls.lesson_text_id
                ,COALESCE(ls.lesson_text_name, lst.lesson_text_name) AS lesson_text_name
                ,ls.teacher_id
                ,tchr.teacher_name
                ,tchr.teacher_nickname
                ,CASE
                    WHEN ls.is_lesson_end = TRUE THEN  2
                    WHEN (SELECT COUNT(*)
         FROM lesson_history WHERE lesson_schedule_id = ls.lesson_schedule_id AND student_id = _student_id AND student_lesson_reserve_type = 1) <> 0 THEN  0
                    WHEN (SELECT COUNT(*)
         FROM lesson_history WHERE lesson_schedule_id = ls.lesson_schedule_id  AND student_lesson_reserve_type = 1) >= 1 THEN   2
                    ELSE COALESCE(ls.lesson_reserve_type,1)
                END AS lesson_reserve_type
                ,lst.lesson_text_url
            FROM
                lesson_schedule ls
                LEFT JOIN teacher tchr ON ls.teacher_id = tchr.teacher_id
                LEFT JOIN lesson_text lst ON lst.lesson_text_id = ls.lesson_text_id
                LEFT JOIN lesson lsn ON ls.lesson_id = lsn.lesson_id
                -- LEFT JOIN student st ON st.student_id = _student_id
                -- LEFT JOIN timezone tz ON tz.timezone_id = st.timezone_id
                INNER JOIN teacher_lesson tl ON tchr.teacher_id = tl.teacher_id  AND tl.lesson_id = _lesson_id
                LEFT JOIN course_lesson cl ON cl.lesson_id = ls.lesson_id
                INNER JOIN course c ON c.course_id = _course_id
                LEFT JOIN point_subscription_history psh ON psh.course_id = _course_id AND psh.student_id = _student_id
            WHERE
                ls.is_lesson_end = 0
                AND ls.teacher_id = _teacher_id
                AND DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y-%m-%d %H:%i:%s')
                    BETWEEN DATE_FORMAT(_lesson_reserve_date_from,'%Y-%m-%d %H:%i:%s')
                    AND DATE_FORMAT(_lesson_reserve_date_to, '%Y-%m-%d 23:59:59')
                AND CASE WHEN c.reserve_end_date IS NULL
                    THEN ls.lesson_starttime >= NOW()
                    ELSE c.reserve_end_date <= ls.lesson_starttime
                    END
                AND ls.lesson_type_id <> 0
                AND ls.lesson_starttime >= DATE_ADD(NOW() ,INTERVAL _time_to_booking MINUTE)
                AND ls.lesson_subscription_type IN(1,3,7)
                AND ((cl.course_id = _course_id AND cl.lesson_id = _lesson_id) OR cl.course_id IS NULL) -- ? de lam gi: cl.course_id IS NULL - chua dat bai hoc, nguoc lai la dat roi va co the cancel
                AND (psh.point_subscription_history_id IS NULL OR psh.point_expire_date >= ls.lesson_starttime)
                AND (psh.point_subscription_history_id IS NULL OR psh.point_expire_date >= NOW())
            GROUP BY ls.lesson_schedule_id
            HAVING lesson_reserve_type <> 2
            ORDER BY
                lesson_date, lesson_starttime, point_expire_date ASC
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
        Schema::dropIfExists('sp_search_student_lesson_reserve_list');
    }
}
