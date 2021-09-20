<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseScheduleSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_schedule_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->nullable();
            $table->string('date_type', 11)->nullable();
            $table->string('start_time', 10)->nullable();
            $table->string('end_time', 10)->nullable();
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
        Schema::dropIfExists('course_schedule_settings');
    }
}
