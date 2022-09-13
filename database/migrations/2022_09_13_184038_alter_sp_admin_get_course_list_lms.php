<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSpAdminGetCourseListLms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_admin_get_course_list_lms`;
        CREATE PROCEDURE `sp_admin_get_course_list_lms`(IN _student_id INT(11),
    IN _course_free_id INT)
BEGIN
  SELECT 
    lpc.project_course_id,
    lpc.project_id,
    lpc.course_type,
    lpc.parent_id,
    c.course_id,
    c.course_name,
    c.point_count,
    IF(lpc.`course_type` =1, 
    (SELECT SUM(lpc1.price)
         FROM lms_project_course lpc1 
         WHERE lpc1.set_course_id = c.course_id AND lpc1.parent_id = lpc.project_course_id), 
    lpc.price
    ) AS price,
    c.is_show
  FROM
    lms_project_student lps
      LEFT JOIN lms_project_course lpc ON lps.project_id = lpc.project_id
    LEFT JOIN course c ON lpc.course_id = c.course_id
     LEFT JOIN lms_project lp ON lp.project_id = lpc.project_id
   WHERE 
     lps.student_id = _student_id
     AND lps.`buy_course_flag` = 1 
     AND lp.buy_course_flag = 1
     AND lpc.course_type <> 2
     AND lps.`delete_flag` = 0 
     AND lpc.`delete_flag` = 0
     AND lpc.`show_flag` = 1
     AND c.is_show = 1
     and c.course_id <> _course_free_id
     AND NOW() BETWEEN (CASE WHEN lpc.start_date IS NULL THEN '1900-01-01 00:00:00' ELSE lpc.start_date END) AND (CASE WHEN lpc.expired_date IS NULL THEN '2900-12-12 23:59:59' ELSE lpc.expired_date END)
		 	AND c.course_type = 0
   ORDER BY lps.`created` DESC, lpc.`order_number`, lpc.`price`
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
