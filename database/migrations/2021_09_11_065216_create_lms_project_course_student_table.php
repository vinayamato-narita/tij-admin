<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsProjectCourseStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_project_course_students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_course_id');
            $table->integer('project_id')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('student_id');
            $table->string('course_begin_month', 6)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('expired_date')->nullable();
            $table->integer('point_subscription_id')->nullable()->unique('point_id');
            $table->string('other_department_management_number', 20)->nullable();
            $table->string('course_code')->nullable();
            $table->string('parent_course_code')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_project_course_students');
    }
}
