<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_text', function (Blueprint $table) {
            $table->increments('lesson_text_id');
            $table->integer('lesson_text_no')->nullable();
            $table->string('lesson_text_url')->nullable();
            $table->string('lesson_text_url_for_teacher')->nullable()->default('');
            $table->string('lesson_text_sound_url')->nullable();
            $table->string('lesson_text_name')->nullable();
            $table->text('lesson_text_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_text');
    }
}
