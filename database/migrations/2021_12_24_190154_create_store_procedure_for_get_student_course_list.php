<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForGetStudentCourseList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_student_course_list`;
        CREATE PROCEDURE `sp_get_student_course_list`(IN _student_id INT,
        IN _lang_type VARCHAR(10))
        BEGIN
          SET @current_course=(SELECT course_id FROM student WHERE student_id =_student_id );

          SELECT
            spt.course_id,
            COALESCE(ci.course_name, c.course_name) AS course_name
          FROM
            student_point_history spt
            LEFT JOIN course c
              ON spt.course_id = c.course_id
            LEFT JOIN course_info ci ON ci.course_id = c.course_id AND ci.lang_type = _lang_type
          WHERE spt.student_id = _student_id
            AND DATE(spt.expire_date) >= DATE(NOW())
            AND CASE
              WHEN @current_course > 1
              THEN spt.course_id > 1
              ELSE 1 = 1
            END
          GROUP BY spt.course_id
          HAVING SUM(spt.point_count) > 0
           ORDER BY c.display_order;
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
        Schema::dropIfExists('sp_get_student_course_list');
    }
}
