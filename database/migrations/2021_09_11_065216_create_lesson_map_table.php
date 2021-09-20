<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_maps', function (Blueprint $table) {
            $table->integer('lesson_id')->nullable();
            $table->integer('togo_lesson_id')->nullable();
            $table->integer('new_lesson_id')->nullable();
            $table->integer('en_lesson_id')->nullable();
            $table->integer('vn_lesson_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_maps');
    }
}
