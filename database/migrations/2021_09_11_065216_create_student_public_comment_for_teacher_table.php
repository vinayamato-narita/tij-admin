<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentPublicCommentForTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_public_comment_for_teacher', function (Blueprint $table) {
            $table->bigIncrements('student_public_comment_for_teacher_id');
            $table->unsignedInteger('teacher_id')->default(0);
            $table->unsignedInteger('student_id')->default(0);
            $table->text('comment');
            $table->dateTime('create_date')->comment('作成日時');
            $table->dateTime('update_date')->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_public_comment_for_teacher');
    }
}
