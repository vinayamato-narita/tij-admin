<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseSetCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_set_course', function (Blueprint $table) {
            $table->increments('course_set_course_id');
            $table->integer('set_course_id')->nullable();
            $table->integer('course_id')->nullable();
            $table->dateTime('modified')->nullable()->comment('更新日時');
            $table->tinyInteger('delete_flag')->nullable()->default(0)->comment('削除フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_set_course');
    }
}
