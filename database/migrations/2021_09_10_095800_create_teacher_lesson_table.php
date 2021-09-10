<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherLessonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_lesson', function (Blueprint $table) {
            $table->integer('teacher_lesson_id')->primary();
            $table->integer('teacher_id')->nullable()->index('teacher_id');
            $table->integer('lesson_id')->nullable()->index('lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_lesson');
    }
}
