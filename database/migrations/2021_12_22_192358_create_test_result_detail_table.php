<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestResultDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_result_detail', function (Blueprint $table) {
            $table->integer('test_result_detail_id')->autoIncrement();
            $table->integer('test_result_id');
            $table->integer('test_id');
            $table->integer('test_question_id');
            $table->text('answer')->nullable();
            $table->text('correct_answer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_result_detail');
    }
}
