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
            $table->increments('course_info_id')->comment('識別ID');
            $table->integer('course_id');
            $table->string('course_name')->nullable();
            $table->text('course_description')->nullable();
            $table->string('lang_type', 10)->comment('言語タイプ。en:英語　vn:ベトナム語');
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
