<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTestComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_comment', function (Blueprint $table) {
            $table->unsignedInteger('test_comment_id')->autoIncrement();
            $table->unsignedInteger('test_result_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('teacher_id');
            $table->dateTime('comment_start_time')->nullable();
            $table->dateTime('comment_end_time')->nullable();
            $table->text('comment1')->nullable();
            $table->text('comment2')->nullable();
            $table->text('comment3')->nullable();
            $table->text('comment4')->nullable();
            $table->text('comment5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
