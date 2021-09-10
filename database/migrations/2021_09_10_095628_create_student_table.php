<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student', function (Blueprint $table) {
            $table->increments('student_id');
            $table->string('student_name');
            $table->string('student_email');
            $table->string('student_password');
            $table->string('student_nickname');
            $table->dateTime('student_birthday')->nullable();
            $table->string('student_skypename')->nullable();
            $table->tinyInteger('student_sex')->nullable()->default(2);
            $table->text('student_introduction');
            $table->text('photo_savepath')->nullable();
            $table->dateTime('last_login_date')->nullable();
            $table->integer('is_tmp_entry')->nullable()->default(1);
            $table->string('is_sercurity')->default('0 COMMENT '仮登録セキュリティ項目'');
            $table->dateTime('expire_date')->nullable();
            $table->integer('student_type')->default(1);
            $table->dateTime('create_date');
            $table->unsignedInteger('lang_type')->default(0);
            $table->unsignedInteger('course_id')->default(0);
            $table->unsignedInteger('course_update_count')->default(0);
            $table->tinyInteger('brand_id');
            $table->integer('timezone_id')->nullable();
            $table->unsignedTinyInteger('is_sending_dm')->nullable()->default(1);
            $table->boolean('direct_mail_flag')->nullable()->default(0);
            $table->unsignedTinyInteger('is_receive_mail')->nullable()->default(0);
            $table->string('company_name')->nullable();
            $table->string('student_first_name')->nullable();
            $table->string('student_last_name')->nullable();
            $table->string('student_first_name_kata')->nullable();
            $table->string('student_last_name_kata')->nullable();
            $table->string('student_home_tel', 20)->nullable();
            $table->string('student_company_tel', 20)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->integer('prefecture_number')->nullable();
            $table->string('student_address', 500)->nullable();
            $table->string('student_address1', 500)->nullable();
            $table->string('student_address2', 500)->nullable();
            $table->string('student_address3', 500)->nullable();
            $table->string('department_name')->nullable();
            $table->string('employee_number', 100)->nullable();
            $table->string('department_number', 100)->nullable();
            $table->boolean('is_lms_user')->nullable()->default(0);
            $table->string('student_name_kana')->nullable();
            $table->text('student_comment_text')->nullable();
            $table->boolean('in_japan_flag')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student');
    }
}
