<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonTextInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_text_info', function (Blueprint $table) {
            $table->increments('lesson_text_info_id');
            $table->integer('lesson_text_id');
            $table->string('lesson_text_name')->nullable();
            $table->text('lesson_text_description')->nullable();
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
        Schema::dropIfExists('lesson_text_info');
    }
}
