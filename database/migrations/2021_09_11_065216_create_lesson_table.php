<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lesson_name')->nullable();
            $table->text('lesson_description')->nullable();
            $table->integer('display_order')->default(1);
            $table->boolean('is_test_lesson')->nullable()->default(0);
            $table->boolean('is_show_to_search')->nullable()->default(1);
            $table->boolean('is_show_to_teacher_detail')->nullable()->default(1);
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
        Schema::dropIfExists('lessons');
    }
}
