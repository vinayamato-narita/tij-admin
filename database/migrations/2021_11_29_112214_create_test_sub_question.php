<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestSubQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_sub_question', function (Blueprint $table) {
            $table->increments('test_sub_question_id');
            $table->integer('test_question_id');
            $table->integer('display_order');
            $table->text('sub_question_content')->nullable();
            $table->text('answer1');
            $table->text('answer2');
            $table->text('answer3')->nullable();
            $table->text('answer4')->nullable();
            $table->text('explanation')->nullable();
            $table->integer('explanation_file_id')->nullable();
            $table->integer('score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_sub_question');
    }
}
