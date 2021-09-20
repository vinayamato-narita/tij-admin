<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherBookmarkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_bookmarks', function (Blueprint $table) {
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('teacher_id');
            $table->primary(['student_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_bookmarks');
    }
}
