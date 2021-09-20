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
        Schema::create('free_teacher_lesson_settings', function (Blueprint $table) {
            $table->increments('setting_id');
            $table->dateTime('lesson_starttime')->nullable()->index('lesson_starttime');
            $table->integer('max_free_lesson')->nullable()->default(0);
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
        Schema::dropIfExists('free_teacher_lesson_settings');
    }
}
