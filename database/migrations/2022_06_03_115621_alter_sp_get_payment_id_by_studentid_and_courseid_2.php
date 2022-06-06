<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSpGetPaymentIdByStudentidAndCourseid2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_payment_id_by_studentid_and_courseid`;
        CREATE PROCEDURE `sp_get_payment_id_by_studentid_and_courseid`(IN _student_id INT,
        IN _course_id INT ,
        IN _expire_date VARCHAR(20),
        OUT _result INT)
        BEGIN
          SELECT point_subscription_id
          FROM student_point_history sph
          JOIN point_subscription_history psh ON psh.point_subscription_history_id=sph.point_subscription_id
          WHERE sph.student_id = _student_id
            AND sph.course_id = _course_id
            AND DATE_FORMAT(sph.expire_date,'%Y-%m-%d') = DATE_FORMAT(_expire_date,'%Y-%m-%d')
            AND psh.payment_status = 1

          GROUP BY point_subscription_id
          HAVING SUM(sph.point_count) > 0
          ORDER BY point_subscription_id
          LIMIT 1
          INTO _result;

        END;
        ";

        \DB::unprepared($procedure);    }

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
