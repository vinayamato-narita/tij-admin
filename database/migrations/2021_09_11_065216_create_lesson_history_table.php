<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_history', function (Blueprint $table) {
            $table->bigIncrements('lesson_history_id');
            $table->integer('lesson_schedule_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->text('comment_from_student_to_teacher')->nullable()->comment('生徒→講師のコメント');
            $table->text('comment_from_teacher_to_student')->nullable()->comment('講師→生徒のコメント');
            $table->text('comment_from_admin_to_student')->nullable()->comment('管理者→生徒のコメント');
            $table->text('comment_from_admin_to_teacher')->nullable()->comment('管理者→講師のコメント');
            $table->text('note_from_student_to_teacher')->comment('生徒のレッスンメモ');
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_history');
    }
}
