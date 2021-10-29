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
            $table->increments('setting_id');
            $table->dateTime('lesson_starttime')->nullable()->index('lesson_starttime')->comment('レッスン開始時間');
            $table->integer('max_free_lesson')->nullable()->default(0)->comment('自由講師が登録可能枠数');
            $table->dateTime('created')->nullable()->comment('作成日時');
            $table->dateTime('modified')->nullable()->comment('更新日時');
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
