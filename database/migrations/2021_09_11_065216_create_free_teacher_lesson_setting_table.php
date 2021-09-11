<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreeTeacherLessonSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_teacher_lesson_setting', function (Blueprint $table) {
            $table->integer('setting_id')->primary();
            $table->dateTime('lesson_starttime')->nullable()->index('lesson_starttime');
            $table->integer('max_free_lesson')->nullable()->default(0);
            $table->dateTime('created')->nullable();
            $table->dateTime('modified')->nullable();
            $table->tinyInteger('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_teacher_lesson_setting');
    }
}
