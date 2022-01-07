<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForInsertStudentPointHistoryByLessonReserveRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_insert_student_point_history_by_lesson_reserve_register`;
        CREATE  PROCEDURE `sp_insert_student_point_history_by_lesson_reserve_register`(IN _lesson_schedule_id BIGINT,
        IN _student_id INT ,
        IN _course_id INT)
        BEGIN
        DECLARE _payment_id   INT;
        DECLARE _lesson_point FLOAT ;
        DECLARE _expire_date VARCHAR(20);
        DECLARE _lesson_type_id INT(10) DEFAULT 0;
        DECLARE _lesson_starttime DATETIME;
        DECLARE _start_date VARCHAR(20);

        SET _lesson_starttime =  NOW();
        SELECT lesson_type_id, lesson_starttime INTO _lesson_type_id, _lesson_starttime FROM lesson_schedule WHERE lesson_schedule_id = _lesson_schedule_id;

            SELECT m.spt,m.exd,m.start_date INTO _lesson_point, _expire_date, _start_date
                                FROM (
                                    SELECT
                                        sph.expire_date AS exd
                                        ,SUM(sph.point_count) AS spt
                                        ,sph.start_date AS start_date
                                      FROM
                                        student_point_history sph
                        LEFT JOIN point_subscription_history psh ON  sph.point_subscription_id = psh.point_subscription_history_id
                                      WHERE
                                        DATE(sph.expire_date) >= DATE(DATE_ADD( NOW(), INTERVAL 90 MINUTE))
                                        AND sph.student_id = _student_id
                                        AND COALESCE(psh.begin_date, sph.start_date) <= _lesson_starttime
                                        AND sph.course_id = _course_id
                                        AND DATE(sph.expire_date) >= DATE(_lesson_starttime)
                                      GROUP BY
                                        DATE(sph.expire_date)
                                      ORDER BY
                                        DATE(sph.expire_date) ASC
                                    ) m
                                    WHERE
                                      m.spt > 0
                                    LIMIT 1;

            CALL sp_get_payment_id_by_studentid_and_courseid(_student_id,_course_id,_expire_date,_payment_id);

            INSERT INTO student_point_history (
                      student_id
                      ,pay_date
                      ,pay_description
                      ,pay_type
                      ,point_count
                      ,expire_date
                      ,lesson_schedule_id
                      ,course_id
                      ,start_date
                      ,point_subscription_id
                    ) VALUES (
                      _student_id
                      , NOW()
                      ,(SELECT CONCAT('使用', ' (', DATE_FORMAT(ls.lesson_date,'%Y/%m/%d'),' ',DATE_FORMAT(ls.lesson_starttime,'%H:%i'), ') ') FROM lesson_schedule ls WHERE ls.lesson_schedule_id = _lesson_schedule_id)
                      ,1
                      ,-1
                      ,_expire_date
                      ,_lesson_schedule_id
                      ,_course_id
                      ,_start_date
                      ,_payment_id
                    )
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
        Schema::dropIfExists('sp_get_payment_id_by_studentid_and_courseid');
    }
}
