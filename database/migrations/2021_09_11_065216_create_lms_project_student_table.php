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
        Schema::create('lms_project_student', function (Blueprint $table) {
            $table->increments('project_student_id');
            $table->integer('project_id')->index('project_id');
            $table->integer('company_id')->nullable();
            $table->integer('student_id')->index('student_id');
            $table->tinyInteger('buy_course_flag')->nullable()->default(1)->comment('購入 0:不可 1:可');
            $table->string('department_name')->nullable();
            $table->string('employee_number', 100)->nullable();
            $table->string('department_number', 100)->nullable();
            $table->dateTime('created')->nullable()->comment('作成日時');
            $table->dateTime('modified')->nullable()->comment('更新日時');
            $table->smallInteger('delete_flag')->nullable()->default(0)->comment('論理削除。1:削除　0:有効');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_project_student');
    }
}
