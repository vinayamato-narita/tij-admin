<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_info', function (Blueprint $table) {
            $table->integer('course_info_id')->primary();
            $table->integer('course_id');
            $table->string('course_name')->nullable();
            $table->text('course_description')->nullable();
            $table->string('lang_type', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_info');
    }
}
