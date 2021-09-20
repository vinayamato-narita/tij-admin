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
        Schema::create('student_public_comment_for_teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('teacher_id')->nullable();
            $table->unsignedInteger('student_id')->nullable();
            $table->text('comment');
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
        Schema::dropIfExists('student_public_comment_for_teachers');
    }
}
