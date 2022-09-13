<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSpAdminGetCourseList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_admin_get_course_list`;
        CREATE PROCEDURE `sp_admin_get_course_list`()
BEGIN
    SELECT
        course_id
        ,course_name
        ,paypal_item_number AS item_number
        ,point_count
        ,is_set_course
        ,IF(is_set_course = 1,
          (SELECT SUM(child.amount)
              FROM course_set_course sc JOIN course child ON sc.course_id = child.course_id 
              WHERE sc.set_course_id = c.course_id),
              amount
             ) AS price
        ,IF(is_set_course = 1,
          (SELECT GROUP_CONCAT(sc.course_id)
              FROM course_set_course sc
              WHERE sc.set_course_id = c.course_id),
              ''
             ) AS child_ids
        ,is_show
    FROM
        course c 
    WHERE c.is_show = 1
			AND c.course_type = 0
		
    ORDER BY
        c.course_name
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
