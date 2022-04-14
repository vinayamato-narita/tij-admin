<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProcedureSpStudentGetPaymentList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_student_get_payment_list`;
        CREATE PROCEDURE `sp_student_get_payment_list`(
            IN _student_id INT,
          IN _lang_type varchar(10)
        )
        BEGIN
        SELECT
            c.course_id
            ,psh.amount
            ,psh.tax
                    ,DATE_FORMAT(psh.payment_date,'%Y/%m/%d') AS payment_date
            ,DATE_FORMAT(psh.point_expire_date,'%Y/%m/%d') AS  point_expire_date 
            ,DATE_FORMAT(psh.begin_date,'%Y/%m/%d')   AS begin_date
            ,COALESCE(ci.course_name, c.course_name) AS course_name
            ,c.course_type
            ,psh.point_subscription_history_id
        FROM
            point_subscription_history psh
            LEFT JOIN course c ON c.course_id = psh.course_id
            LEFT JOIN course_info ci ON ci.course_id = c.course_id AND ci.lang_type = _lang_type
            LEFT JOIN student s
                 ON psh.student_id = s.student_id        
        WHERE
            psh.student_id = _student_id
            AND psh.del_flag = 0
        ORDER BY
            psh.payment_date DESC
        ;
        END";

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
