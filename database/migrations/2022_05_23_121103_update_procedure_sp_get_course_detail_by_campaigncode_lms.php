<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProcedureSpGetCourseDetailByCampaigncodeLms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_course_detail_by_campaigncode_lms`;
        CREATE PROCEDURE `sp_get_course_detail_by_campaigncode_lms`(IN _student_id INT,
        IN _course_id INT,
        IN _lang_type VARCHAR(10))
        BEGIN
          SELECT
            lms_project_course.project_course_id,
            lms_project_course.project_id,
            course.course_id,
            COALESCE(ci.course_name, course.course_name) AS course_name,
            COALESCE(ci.course_description, course.course_description) AS course_description,
            course.amount,
            course.course_type,
            course.expire_day,
            course.min_reserve_count,
            DATE_FORMAT(course.decide_date, '%Y/%m/%d %H:%i') as decide_date_show,
            DATE_FORMAT(course.course_start_date, '%Y/%m/%d %H:%i') as course_start_date,
            DATE_FORMAT(course.publish_date_to, '%Y/%m/%d %H:%i') as publish_date_to,
            IF(lms_project_course.`course_type` =1,
            (SELECT SUM(lpc.price)
                 FROM lms_project_course lpc
                 WHERE lpc.set_course_id = course.course_id AND lpc.parent_id = lms_project_course.project_course_id),
            lms_project_course.price
            ) AS price,
            lms_project.`corporation_flag`,
            lms_project.payment_type
          FROM
            (
              lms_project_student
              LEFT JOIN lms_project_course
                ON lms_project_student.project_id = lms_project_course.project_id
            )
            LEFT JOIN course
              ON lms_project_course.course_id = course.course_id
             LEFT JOIN course_info ci ON ci.course_id = course.course_id AND ci.lang_type = _lang_type
             LEFT JOIN lms_project ON lms_project.project_id = lms_project_course.project_id
          WHERE
            lms_project_student.student_id = _student_id
            AND lms_project_student.`buy_course_flag` = 1
            AND lms_project.buy_course_flag = 1
            AND lms_project_course.`course_type` <> 2 -- not get child course of set course
            AND lms_project_student.`delete_flag` = 0
            AND lms_project_course.`delete_flag` = 0
            AND lms_project_course.`show_flag` = 1
            AND course.course_id = _course_id
            AND NOW() BETWEEN (CASE WHEN lms_project_course.start_date IS NULL THEN '1900-01-01 00:00:00' ELSE lms_project_course.start_date END) AND (CASE WHEN lms_project_course.expired_date IS NULL THEN '2900-12-12 23:59:59' ELSE lms_project_course.expired_date END);
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
