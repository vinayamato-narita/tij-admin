<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_map', function (Blueprint $table) {
            $table->integer('lesson_text_id')->nullable();
            $table->integer('togo_lesson_text_id')->nullable();
            $table->integer('new_text_id')->nullable();
            $table->integer('en_lesson_text_id')->nullable();
            $table->integer('vn_lesson_text_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('text_map');
    }
}
