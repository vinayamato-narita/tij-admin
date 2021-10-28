<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_video', function (Blueprint $table) {
            $table->increments('course_video_id');
            $table->integer('course_id');
            $table->string('video_name');
            $table->string('video_url');
            $table->integer('type')->nullable()->default(0);
            $table->string('image_url')->nullable();
            $table->dateTime('created')->nullable();
            $table->dateTime('modified')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_video');
    }
}
