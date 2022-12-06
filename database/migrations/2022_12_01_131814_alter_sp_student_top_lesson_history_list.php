<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSpStudentTopLessonHistoryList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
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
                                ,COALESCE(ci.course_name, c.course_name, '') as course_name
								,t.teacher_name
								,t.photo_savepath
								,ls.zoom_url
								,f.file_path
								,f.file_name_original
                ,p.point_subscription_history_id
								,fp.file_path as prep_file_path
             FROM
                lesson_history lh
                LEFT JOIN lesson_schedule ls ON lh.lesson_schedule_id = ls.lesson_schedule_id
                LEFT JOIN course c ON ls.course_id = c.course_id
                LEFT JOIN course_info ci on ci.course_id = c.course_id AND ci.lang_type = _lang_type
                LEFT JOIN teacher tchr ON ls.teacher_id = tchr.teacher_id
                LEFT JOIN teacher_info ti on ti.teacher_id = tchr.teacher_id AND ti.lang_type = _lang_type
                LEFT JOIN lesson lsn ON ls.lesson_id = lsn.lesson_id
                LEFT JOIN lesson_info li on li.lesson_id = lsn.lesson_id AND li.lang_type = _lang_type
								LEFT JOIN lesson_text_lesson ltl on ls.lesson_id = ltl.lesson_id
                LEFT JOIN lesson_text lst ON ltl.lesson_text_id = lst.lesson_text_id
								LEFT JOIN file f ON lst.lesson_text_student_file_id = f.file_id
                LEFT JOIN lesson_text_info lti on lti.lesson_text_id = lst.lesson_text_id AND lti.lang_type = _lang_type
								LEFT JOIN preparation_lesson pl on ls.lesson_id = pl.lesson_id
								LEFT JOIN preparation pre on pl.preparation_id = pre.preparation_id
								LEFT JOIN file fp ON pre.file_id = fp.file_id
								
								
                LEFT JOIN student s ON s.student_id = lh.student_id
								LEFT JOIN teacher t ON t.teacher_id = ls.teacher_id
                LEFT JOIN point_subscription_history p
                        ON p.point_subscription_history_id = (
                            SELECT point_subscription_history_id
                            FROM point_subscription_history
                            WHERE
                                student_id = _student_id
                                AND payment_status = 1
                                AND TIMESTAMP(point_expire_date) >= TIMESTAMP(NOW())
                                AND course_id = ls.course_id
                            ORDER BY point_subscription_history_id DESC
                            LIMIT 1
                        )

            WHERE
                lh.student_id = _student_id
                AND DATE_FORMAT(ls.lesson_endtime,'%Y-%m-%d %H-%i') >= DATE_FORMAT(NOW(),'%Y-%m-%d %H-%i')
                AND lh.student_lesson_reserve_type <> 2
				AND ls.course_id IS NOT NULL
            ORDER BY
                lesson_date ASC
                ,lesson_starttime ASC
            LIMIT _limit
            ;
        END
        ";

        \DB::unprepared($procedure);;
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
