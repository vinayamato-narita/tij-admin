<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsProjectStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_project_students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->index('project_id');
            $table->integer('company_id')->nullable();
            $table->integer('student_id')->index('student_id');
            $table->boolean('buy_course_flag')->nullable()->default(1);
            $table->string('department_name')->nullable();
            $table->string('employee_number', 100)->nullable();
            $table->string('department_number', 100)->nullable();
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
        Schema::dropIfExists('lms_project_students');
    }
}
