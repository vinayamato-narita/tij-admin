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
            $table->integer('course_id')->primary()->comment('現在のコースID');
            $table->integer('togo_course_id')->nullable()->comment('統合コースID');
            $table->integer('new_course_id')->nullable()->comment('統合システムに導入した時');
            $table->integer('en_course_id')->nullable()->comment('EN版');
            $table->integer('vn_course_id')->nullable()->comment('VN版');
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
