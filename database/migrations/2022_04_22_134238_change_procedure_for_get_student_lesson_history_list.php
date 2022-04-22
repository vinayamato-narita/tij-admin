<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProcedureForGetStudentLessonHistoryList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_student_lesson_history_list`;
        CREATE PROCEDURE `sp_student_lesson_history_list`(IN _student_id int,
        IN _limit INT,
        IN _course_id INT,
        IN _lesson_id INT,
        IN _lang_type VARCHAR(10))
        BEGIN

            SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM  timezone tz WHERE tz.timezone_id = 1 LIMIT 1),9 * 60);

            SELECT
                lh.lesson_history_id,
                lh.lesson_schedule_id,
                lh.student_id,
                lh.student_lesson_start,
                ls.teacher_id,
                COALESCE(ti.teacher_name, tchr.teacher_name) AS  teacher_name,
                COALESCE(ti.teacher_nickname, tchr.teacher_nickname) AS  teacher_nickname,
                tchr.teacher_skypename,
            ca.category_id,
            COALESCE(cai.category_name, ca.category_name) as category_name,
                tchr.photo_savepath,
                lsn.lesson_id,
                COALESCE(li.lesson_name,lsn.lesson_name) as lesson_name,
                lst.lesson_text_id,
                COALESCE(lti.lesson_text_name,lst.lesson_text_name) lesson_text_name,
                 (SELECT COUNT(*) FROM teacher_bookmark WHERE student_id = _student_id AND teacher_id = ls.teacher_id) AS is_bookmarked,
                DATE_FORMAT(DATE_ADD(DATE_ADD(ls.lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE),'%Y-%m-%d') AS lesson_date,
                CONCAT(
                    DATE_FORMAT(DATE_ADD(DATE_ADD(ls.lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE),'%H:%i')
                    ,'~',
                    DATE_FORMAT(DATE_ADD(DATE_ADD(ls.lesson_endtime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE),'%H:%i')) AS lesson_time,
                DATE_ADD(DATE_ADD(ls.lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE) AS lesson_starttime,
                DATE_ADD(DATE_ADD(ls.lesson_endtime,INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE) AS lesson_endtime,

                lh.student_lesson_reserve_type
                ,ls.is_lesson_end
                ,lst.lesson_text_url
                ,lh.reserve_date
                ,p.course_id
                ,c.course_type
                ,COALESCE(ci.course_name, c.course_name) as course_name
            ,lsn.display_order
            ,lst.lesson_text_no
            ,DATE_ADD(DATE_ADD(DATE_ADD(p.pay_date, INTERVAL c.expire_day DAY),INTERVAL -@time_diff_from MINUTE),INTERVAL tz.diff_time * 60 MINUTE) as course_end_date
            FROM
                lesson_history lh
                LEFT JOIN lesson_schedule ls ON lh.lesson_schedule_id = ls.lesson_schedule_id
                LEFT JOIN teacher tchr ON ls.teacher_id = tchr.teacher_id
                LEFT JOIN teacher_info ti ON ti.teacher_id = tchr.teacher_id AND ti.lang_type = _lang_type
                LEFT JOIN lesson lsn ON ls.lesson_id = lsn.lesson_id
                LEFT JOIN lesson_info li ON li.lesson_id = lsn.lesson_id AND li.lang_type = _lang_type
                LEFT JOIN lesson_text lst ON ls.lesson_text_id = lst.lesson_text_id
                LEFT JOIN lesson_text_info lti ON lti.lesson_text_id = lst.lesson_text_id AND lti.lang_type = _lang_type
                LEFT JOIN student st ON st.student_id = lh.student_id
                LEFT JOIN timezone tz ON tz.timezone_id = st.timezone_id
                LEFT JOIN student_point_history p ON p.lesson_schedule_id = ls.lesson_schedule_id
                LEFT JOIN course c ON c.course_id = p.course_id
                LEFT JOIN course_info ci ON ci.course_id = c.course_id AND ci.lang_type = _lang_type
            LEFT JOIN category_course as cc ON cc.course_id = c.course_id
            LEFT JOIN category ca ON ca.category_id = cc.category_id
            LEFT JOIN category_info cai ON cai.category_id = ca.category_id AND cai.lang_type = _lang_type
            WHERE
                lh.student_id = _student_id
                AND ls.course_id IS NOT NULL
                AND ls.lesson_endtime < NOW()
                AND CASE _course_id  WHEN 0 THEN 1=1 ELSE p.course_id = _course_id END
                AND CASE _lesson_id WHEN 0 THEN 1=1 ELSE lsn.lesson_id = _lesson_id END
            ORDER BY
                 ls.lesson_date DESC,
                ls.lesson_starttime DESC
            LIMIT _limit
            ;
        END;
        ";

        DB::unprepared($procedure);

        $procedure1 = "
        DROP PROCEDURE IF EXISTS `sp_student_top_lesson_history_list`;
        CREATE PROCEDURE `sp_student_top_lesson_history_list`(IN _student_id int,
            IN _limit INT,
            IN _lang_type varchar(10))
        BEGIN
            SET @timezone_id = COALESCE((SELECT timezone_id FROM student WHERE student_id = _student_id),1);
            SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM timezone tz WHERE timezone_id = 1 LIMIT 1),9 * 60);
            SET @time_diff_to = COALESCE((SELECT diff_time * 60 FROM timezone WHERE timezone_id = @timezone_id),9 * 60);

            SELECT
                lh.lesson_history_id,
                lh.lesson_schedule_id,
                ls.teacher_id,
                COALESCE(ti.teacher_nickname, tchr.teacher_nickname) AS  teacher_nickname,
                tchr.teacher_skypename,
                tchr.photo_savepath,
                lsn.lesson_id,
                COALESCE(li.lesson_name, lsn.lesson_name, '') as lesson_name,
                lst.lesson_text_id,
                lst.lesson_text_url,
                lst.lesson_text_sound_url,
                COALESCE(lti.lesson_text_name, lst.lesson_text_name, '') AS lesson_text_name,
                DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y-%m-%d') AS lesson_date
                ,CONCAT(DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%H:%i'),'~',DATE_FORMAT(DATE_ADD(DATE_ADD(lesson_endtime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%H:%i')) AS lesson_time
                ,DATE_ADD(DATE_ADD(ls.lesson_starttime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE) AS lesson_starttime
								,DATE_ADD(DATE_ADD(ls.lesson_endtime,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE) AS lesson_endtime
								,ls.course_type
								,ls.course_id
								,t.teacher_name
								,t.photo_savepath
								,ls.zoom_url
								,f.file_path
								,f.file_name_original
             FROM
                lesson_history lh
                LEFT JOIN lesson_schedule ls ON lh.lesson_schedule_id = ls.lesson_schedule_id
                LEFT JOIN teacher tchr ON ls.teacher_id = tchr.teacher_id
                LEFT JOIN teacher_info ti on ti.teacher_id = tchr.teacher_id AND ti.lang_type = _lang_type
                LEFT JOIN lesson lsn ON ls.lesson_id = lsn.lesson_id
                LEFT JOIN lesson_info li on li.lesson_id = lsn.lesson_id AND li.lang_type = _lang_type
								LEFT JOIN lesson_text_lesson ltl on ls.lesson_id = ltl.lesson_id
                LEFT JOIN lesson_text lst ON ltl.lesson_text_id = lst.lesson_text_id
								LEFT JOIN file f ON lst.lesson_text_student_file_id = f.file_id
                LEFT JOIN lesson_text_info lti on lti.lesson_text_id = lst.lesson_text_id AND lti.lang_type = _lang_type
                LEFT JOIN student s ON s.student_id = lh.student_id
								LEFT JOIN teacher t ON t.teacher_id = ls.teacher_id

            WHERE
                lh.student_id = _student_id
                AND DATE_FORMAT(ls.lesson_starttime,'%Y-%m-%d %H-%i') >= DATE_FORMAT(NOW(),'%Y-%m-%d %H-%i')
                AND lh.student_lesson_reserve_type <> 2
				AND ls.course_id IS NOT NULL
            ORDER BY
                lesson_date DESC
                ,lesson_starttime DESC
            LIMIT _limit
            ;
        END";

        DB::unprepared($procedure1);
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
