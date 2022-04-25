<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSpUpdateLessonTeacherRating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_update_lesson_teacher_rating`;
        CREATE PROCEDURE `sp_update_lesson_teacher_rating`(
            IN _lesson_history_id INT(10),
            IN _teacher_rating INT(11),
            IN _teacher_attitude INT(11),
            IN _teacher_punctual INT(11),
            IN _skype_voice_rating_from_student INT(11),
            IN _comment_from_student_to_office text CHARSET utf8,
            IN _comment_from_student_to_teacher text CHARSET utf8
            )
        BEGIN
                 UPDATE lesson_history
                 SET
                         teacher_rating = _teacher_rating
                         ,teacher_attitude = _teacher_attitude
                         ,teacher_punctual = _teacher_punctual
                         ,skype_voice_rating_from_student = _skype_voice_rating_from_student
                         ,comment_from_student_to_teacher = _comment_from_student_to_teacher
                         ,comment_from_student_to_office = _comment_from_student_to_office
                         ,accept_comment_to_teacher = 1
                  WHERE lesson_history_id = _lesson_history_id
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
