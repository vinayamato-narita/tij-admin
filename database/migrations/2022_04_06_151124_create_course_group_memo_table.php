<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseGroupMemoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_group_memo', function (Blueprint $table) {
            $table->increments('course_group_memo_id');
            $table->integer('course_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->text('memo')->nullable();
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
        Schema::dropIfExists('course_group_memo');
    }
}
