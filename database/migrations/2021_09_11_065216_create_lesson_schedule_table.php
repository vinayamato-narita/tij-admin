<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_schedule', function (Blueprint $table) {
            $table->increments('lesson_schedule_id')->comment('識別ID');
            $table->dateTime('lesson_date')->index('lesson_date');
            $table->dateTime('lesson_starttime')->index('lesson_starttime');
            $table->dateTime('lesson_endtime');
            $table->unsignedInteger('lesson_id');
            $table->unsignedInteger('lesson_text_id');
            $table->unsignedInteger('teacher_id')->index('teacher_id');
            $table->unsignedInteger('lesson_type_id')->index('lesson_type_id')->comment('レッスンタイプ。0:-　１：registered');
            $table->tinyInteger('is_lesson_end')->index('is_lesson_end')->comment('０:未終了　１：終了');
            $table->unsignedInteger('lesson_reserve_type')->default(1)->comment('１：未予約　２：予約済');
            $table->unsignedInteger('lesson_subscription_type')->default(0);
            $table->string('lesson_text_name', 100)->nullable();
            $table->dateTime('last_update_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_schedule');
    }
}
