<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSpGetStudentInfoByLessonScheduleId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_student_info_by_lesson_schedule_id`;
        CREATE PROCEDURE `sp_get_student_info_by_lesson_schedule_id`(
            IN _lesson_schedule_id INT(10))
        BEGIN
            SELECT	s.student_id,
                            s.student_name,
                            s.student_nickname,
                            DATE_FORMAT(lh.reserve_date,'%Y-%m-%d %H:%i:%s') AS reserve_date,
                            s.student_skypename,
                            s.student_email,
                            COALESCE(lh.course_id,0) AS course_id,
                            COALESCE(l.lesson_name,'') AS lesson_name,
                            COALESCE(c.course_type, null) AS course_type
            FROM lesson_history lh
            LEFT JOIN student s ON lh.student_id = s.student_id
            LEFT JOIN lesson_schedule ls ON ls.lesson_schedule_id = lh.lesson_schedule_id
            LEFT JOIN lesson l ON l.lesson_id = ls.lesson_id
            LEFT JOIN course c ON c.course_id = lh.course_id
            WHERE lh.lesson_schedule_id = _lesson_schedule_id
            ORDER BY reserve_date DESC
        ;
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
