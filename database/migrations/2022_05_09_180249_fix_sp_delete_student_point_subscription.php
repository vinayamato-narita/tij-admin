<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSpDeleteStudentPointSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `sp_admin_delete_student_point_subscription`;
CREATE PROCEDURE `sp_admin_delete_student_point_subscription`(IN _point_subscription_history_id INT,
    IN _cancel_history_save_flag INT)
BEGIN
    IF _cancel_history_save_flag = 1 THEN
        INSERT INTO lesson_cancel_history (
            `student_id`,
            `lesson_schedule_id`,
            `teacher_id`,
            `lesson_date`,
            `lesson_starttime`,
            `reserve_date`,
            `cancel_date`,
            `cancel_student_comment`,
            `cancel_admin_comment`,
            `cancel_teacher_comment`
        ) 
        SELECT 
                    lh.student_id,
                    lh.lesson_schedule_id,
                    ls.teacher_id,
                    ls.lesson_date,
                    ls.lesson_starttime,
                    lh.reserve_date,
                    NOW(),
                    '',
                    '',
                    ''
                FROM 
                    lesson_history lh
                    join lesson_schedule ls on lh.lesson_schedule_id = ls.lesson_schedule_id
                    JOIN student_point_history sph ON sph.lesson_schedule_id = ls.lesson_schedule_id and lh.student_id = sph.student_id
                    
                WHERE
                    sph.point_subscription_id = _point_subscription_history_id;
        END IF;
        
        DELETE FROM lesson_schedule
        WHERE lesson_schedule_id IN 
        (SELECT lesson_schedule_id FROM student_point_history WHERE point_subscription_id = _point_subscription_history_id) AND course_type = 0;
        
        DELETE FROM lesson_history
        WHERE lesson_schedule_id IN
        (
        SELECT lesson_schedule_id FROM student_point_history WHERE point_subscription_id = _point_subscription_history_id
        ) AND student_id IN 
        (
        SELECT student_id FROM student_point_history WHERE point_subscription_id = _point_subscription_history_id
        );
        
        DELETE FROM student_point_history WHERE point_subscription_id = _point_subscription_history_id;
        
        DELETE FROM point_subscription_history WHERE point_subscription_history_id = _point_subscription_history_id;
        
        DELETE FROM lms_project_course_student WHERE point_subscription_id = _point_subscription_history_id;
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
