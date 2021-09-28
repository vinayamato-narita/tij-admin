<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonHistoryOldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_history_olds', function (Blueprint $table) {
            $table->id();
            $table->integer('lesson_history_id')->nullable();
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
            $table->unsignedInteger('student_lesson_reserve_type')->default(1)->index('student_lesson_reserve_type');
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
            $table->index(['lesson_schedule_id', 'student_lesson_reserve_type'], 'lesson_schedule_id');
            $table->index(['student_id', 'student_lesson_reserve_type'], 'student_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_history_olds');
    }
}
