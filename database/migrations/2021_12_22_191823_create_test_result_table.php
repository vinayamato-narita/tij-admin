<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_result', function (Blueprint $table) {
            $table->integer('test_result_id')->autoIncrement();
            $table->integer('test_id');
            $table->integer('student_id');
            $table->integer('course_id');
            $table->integer('lesson_schedule_id')->nullable();
            $table->integer('lesson_id')->nullable();
            $table->dateTime('test_start_time');
            $table->dateTime('test_end_time')->nullable();
            $table->integer('total_score');
            $table->dateTime('last_update_date');
            $table->integer('point_subscription_history_id')->nullable();
            $table->smallInteger('is_reviewed')->default();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_result');
    }
}
