<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonCancelHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_cancel_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('teacher_id');
            $table->dateTime('lesson_date');
            $table->dateTime('lesson_starttime');
            $table->dateTime('reserve_date');
            $table->dateTime('cancel_date');
            $table->text('cancel_student_comment');
            $table->text('cancel_admin_comment');
            $table->text('cancel_teacher_comment');
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
        Schema::dropIfExists('lesson_cancel_histories');
    }
}
