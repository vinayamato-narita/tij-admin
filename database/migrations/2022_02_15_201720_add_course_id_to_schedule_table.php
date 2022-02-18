<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseIdToScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_schedule', function (Blueprint $table) {
            $table->unsignedInteger('course_id')->after('lesson_text_id')->nullable();
            $table->smallInteger('course_type')->after('course_id')->nullable()->default(0)->comment('普通コース：0、セットコース :1、セットコースの子コース：２');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_schedule', function (Blueprint $table) {
            //
        });
    }
}
