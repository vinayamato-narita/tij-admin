<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpTeacherLessonHistoryRanking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "CREATE PROCEDURE `sp_teacher_lesson_history_ranking`(IN _lesson_history_id int,
IN _student_rating int,
IN _comment_from_teacher_to_student text,
IN _note_from_teacher_to_student text,
IN _skype_voice_rating_from_teacher INT,
IN _comment_from_teacher_to_office text,
IN _marks INT)
BEGIN
    UPDATE lesson_history
    SET
        student_rating = _student_rating,
        comment_from_teacher_to_student = _comment_from_teacher_to_student,
        note_from_teacher_to_student = _note_from_teacher_to_student,
        accept_comment_to_student = 1,
        skype_voice_rating_from_teacher = _skype_voice_rating_from_teacher,
        comment_from_teacher_to_office = _comment_from_teacher_to_office,
        marks = _marks
    WHERE
        lesson_history_id = _lesson_history_id
    ;
END";
        \DB::unprepared($procedure1);    }

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
