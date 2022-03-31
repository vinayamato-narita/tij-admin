<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpGetStudentHistoryList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `get_student_point_history_list`;
        CREATE PROCEDURE `get_student_point_history_list`(
            IN _student_id int,
            IN _lang_type VARCHAR(10)
            )
            BEGIN
                SELECT
                    student_point_history_id
                    ,DATE_FORMAT(pay_date,'%Y/%m/%d')   as execute_date
                    ,pay_description    as point_detail
                    ,sph.point_count
                    ,DATE_FORMAT(expire_date,'%Y年%m月%d日') as  expire_date
                    ,pay_type
                    ,sph.lesson_schedule_id
                    ,COALESCE(ci.course_name, c.course_name) AS course_name
                    ,c.course_id
                            ,sph.point_subscription_id as payment_id
                            ,DATE_FORMAT(sph.start_date,'%Y/%m/%d') AS begin_date
                            ,COALESCE(sci.course_name, sc.course_name) AS set_course_name
                    ,sc.course_id
                    ,psh.set_course_id
                    ,psh.order_id
                            ,lc.lesson_schedule_id
                            ,DATE_FORMAT(lc.lesson_starttime,'%H:%m') AS  lesson_starttime
                            ,DATE_FORMAT(lc.lesson_endtime,'%H:%m') AS  lesson_endtime
                            ,DATE_FORMAT(lc.lesson_date,'%Y/%m/%d') AS  lesson_date
                            ,t.teacher_id
                            ,t.teacher_name
                            ,c.course_type
                            ,l.lesson_id
                            ,t.photo_savepath
                FROM
                    student_point_history sph
                    LEFT JOIN course c on c.course_id = sph.course_id
                    LEFT JOIN course_info ci ON ci.course_id = c.course_id AND ci.lang_type = _lang_type
                    LEFT JOIN point_subscription_history psh
                       ON psh.point_subscription_history_id = sph.point_subscription_id
                    LEFT JOIN course sc ON psh.set_course_id = sc.course_id
                    LEFT JOIN course_info sci ON sci.course_id = sc.course_id AND sci.lang_type = _lang_type
                            LEFT JOIN lesson_schedule lc ON sph.lesson_schedule_id = lc.lesson_schedule_id
                    LEFT JOIN teacher t ON lc.teacher_id = t.teacher_id
                WHERE
                    sph.student_id = _student_id
                            AND sph.point_count < 0
                ORDER BY
                    pay_date DESC
                ;
            END";

        \DB::unprepared($procedure);

        $procedureLms = "
        DROP PROCEDURE IF EXISTS `lms_get_student_point_history_list`;
        CREATE PROCEDURE `lms_get_student_point_history_list`(
        IN _student_id INT,
        IN _lang_type varchar(10)
        )
        BEGIN
            SELECT
                student_point_history_id
                ,DATE_FORMAT(pay_date,'%Y/%m/%d')   AS execute_date
                ,pay_description AS point_detail
                ,sph.point_count
                ,DATE_FORMAT(sph.expire_date,'%Y/%m/%d') AS  expire_date
                ,pay_type
                ,sph.lesson_schedule_id
                ,COALESCE(ci.course_name, c.course_name) AS course_name
                ,c.course_id
                ,COALESCE(sci.course_name, sc.course_name) AS set_course_name
                ,c.course_id
                ,psh.set_course_id
                        ,sph.point_subscription_id AS payment_id
                        ,psh.payment_way
                        ,psh.paid_status
                        ,psh.order_id
                        ,od.cvs_code
                        ,od.cvs_conf_no
                        ,od.cvs_receipt_no
                        ,od.payment_term
                        ,s.student_name_kana
                        ,s.student_home_tel
                        ,DATE_FORMAT(psh.begin_date,'%Y/%m/%d') AS  begin_date
                        ,lpc.project_course_id
                        ,lc.lesson_schedule_id
                        ,DATE_FORMAT(lc.lesson_starttime,'%H:%m') AS  lesson_starttime
                        ,DATE_FORMAT(lc.lesson_endtime,'%H:%m') AS  lesson_endtime
                        ,DATE_FORMAT(lc.lesson_date,'%Y/%m/%d') AS  lesson_date
                        ,t.teacher_id
                        ,t.teacher_name
                        ,c.course_type
                        ,COALESCE(li.lesson_name, l.lesson_name) AS lesson_name
                        ,l.lesson_id
                        ,t.photo_savepath
            FROM
                student_point_history sph
                LEFT JOIN course c ON c.course_id = sph.course_id
                LEFT JOIN course_info ci ON ci.course_id = c.course_id AND ci.lang_type = _lang_type
                LEFT JOIN point_subscription_history psh
                    ON psh.point_subscription_history_id = sph.point_subscription_id
                LEFT JOIN course sc ON psh.set_course_id = sc.course_id
                LEFT JOIN course_info sci ON sci.course_id = sc.course_id AND sci.lang_type = _lang_type
                LEFT JOIN `order` od
                    ON psh.order_id = od.order_id
                LEFT JOIN student s
                    ON sph.student_id = s.student_id
                LEFT JOIN lms_project_course_student lpc ON lpc.student_id = _student_id AND psh.point_subscription_history_id = lpc.point_subscription_id
                        LEFT JOIN lesson_schedule lc ON sph.lesson_schedule_id = lc.lesson_schedule_id
                LEFT JOIN teacher t ON lc.teacher_id = t.teacher_id
                        LEFT JOIN lesson l ON lc.lesson_id = l.lesson_id
                LEFT JOIN lesson_info li ON li.lesson_id = lc.lesson_id AND li.lang_type = _lang_type
            WHERE
                sph.student_id = _student_id
                        AND sph.point_count < 0
            ORDER BY
                pay_date DESC
            ;
        END";

        \DB::unprepared($procedureLms);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sp_get_student_history_list');
    }
}
