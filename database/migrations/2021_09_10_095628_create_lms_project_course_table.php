<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsProjectCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_project_course', function (Blueprint $table) {
            $table->integer('project_course_id')->primary();
            $table->integer('company_id')->nullable();
            $table->integer('project_id')->index('project_id');
            $table->integer('course_id')->index('course_id');
            $table->integer('set_course_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->smallInteger('course_type')->nullable()->default(0);
            $table->string('course_code', 15)->nullable();
            $table->float('price', 10, 0)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('bill_address')->nullable();
            $table->float('complete_require', 10, 2)->nullable()->default(0.00 COMMENT '修了条件（%）');
            $table->smallInteger('complete_require_marks')->nullable()->default(0);
            $table->smallInteger('complete_require_type')->nullable()->default(1);
            $table->boolean('complete_certificate_flag')->nullable()->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('expired_date')->nullable();
            $table->integer('max_student_num')->nullable()->default(0);
            $table->dateTime('created')->nullable();
            $table->dateTime('modified')->nullable();
            $table->boolean('delete_flag')->nullable()->default(0)->index('delete_flag');
            $table->string('education_corporation_order_number', 12)->nullable();
            $table->boolean('tsv_import_flag')->nullable()->default(0);
            $table->boolean('score_grace_period')->nullable()->default(0);
            $table->boolean('start_date_option')->nullable()->default(1);
            $table->boolean('certificate_download_target')->nullable()->default(1);
            $table->integer('order_number')->nullable()->default(0);
            $table->boolean('show_flag')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_project_course');
    }
}
