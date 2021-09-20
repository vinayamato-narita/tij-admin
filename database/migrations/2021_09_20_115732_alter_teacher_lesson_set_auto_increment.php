<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTeacherLessonSetAutoIncrement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_lesson', function (Blueprint $table) {
            $table->dropColumn('teacher_lesson_id');
        });

        Schema::table('teacher_lesson', function (Blueprint $table) {
            $table->bigIncrements('teacher_lesson_id');
        });
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
