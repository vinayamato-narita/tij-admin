<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonHistoryOldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_history_old', function (Blueprint $table) {
            $table->unsignedInteger('lesson_history_id')->default(0);
            $table->integer('lesson_schedule_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->text('comment_from_student_to_teacher')->nullable();
            $table->text('comment_from_teacher_to_student')->nullable();
            $table->text('comment_from_admin_to_student')->nullable();
            $table->text('comment_from_admin_to_teacher')->nullable();
            $table->text('note_from_student_to_teacher');
            $table->integer('teacher_rating')->nullable()->default(0);
            $table->integer('student_rating')->nullable()->default(0);
            $table->text('note_from_teacher_to_student')->nullable();
            $table->unsignedInteger('student_lesson_reserve_type')->default(1);
            $table->dateTime('reserve_date');
            $table->unsignedTinyInteger('accept_comment_to_student')->nullable()->default(1);
            $table->unsignedTinyInteger('accept_comment_to_teacher')->nullable()->default(1);
            $table->unsignedInteger('skype_voice_rating_from_student')->nullable()->default(0);
            $table->unsignedInteger('skype_voice_rating_from_teacher')->nullable()->default(0);
            $table->unsignedInteger('teacher_attitude')->nullable()->default(0);
            $table->unsignedInteger('teacher_punctual')->nullable()->default(0);
            $table->text('comment_from_student_to_office')->nullable();
            $table->text('comment_from_teacher_to_office')->nullable();
            $table->integer('course_id')->nullable();
            $table->smallInteger('marks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_history_old');
    }
}
