<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_map', function (Blueprint $table) {
            $table->integer('course_id')->primary();
            $table->integer('togo_course_id')->nullable();
            $table->integer('new_course_id')->nullable();
            $table->integer('en_course_id')->nullable();
            $table->integer('vn_course_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_map');
    }
}
