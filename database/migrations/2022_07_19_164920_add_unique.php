<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("lesson_text_lesson", function (Blueprint $table) {
            $table->unique(['lesson_id', 'lesson_text_id']);
        });

        Schema::table("course_test", function (Blueprint $table) {
            $table->unique(['course_id', 'test_id']);
        });

        Schema::table("lesson_test", function (Blueprint $table) {
            $table->unique(['lesson_id', 'test_id']);
        });

        Schema::table("preparation_lesson", function (Blueprint $table) {
            $table->unique(['lesson_id', 'preparation_id']);
        });

        Schema::table("review_lesson", function (Blueprint $table) {
            $table->unique(['lesson_id', 'review_id']);
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
