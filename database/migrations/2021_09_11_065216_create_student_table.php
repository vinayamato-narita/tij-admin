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
            $table->string('password')->comment('パスワード（md5で暗号化）');
            $table->string('student_nickname');
            $table->dateTime('student_birthday')->nullable();
            $table->string('student_skypename')->nullable();
            $table->tinyInteger('student_sex')->nullable()->default(2);
            $table->text('student_introduction');
            $table->text('photo_savepath')->nullable();
            $table->dateTime('last_login_date')->nullable();
            $table->integer('is_tmp_entry')->nullable()->default(1)->comment('仮登録フラグ。０：利用中、１：仮登録、２：無効');
            $table->string('remember_token')->nullable()->comment('仮登録セキュリティ項目');
            $table->dateTime('remember_token_expires_at')->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->integer('student_type')->default(1)->comment('生徒タイプ。無料会員か有用会員か');
            $table->dateTime('create_date')->nullable()->comment('作成日時');
            $table->unsignedInteger('lang_type')->default(0);
            $table->unsignedInteger('course_id')->default(0);
            $table->unsignedInteger('course_update_count')->default(0);
            $table->integer('timezone_id')->nullable();
            $table->unsignedTinyInteger('is_sending_dm')->nullable()->default(1)->comment('督促メール　1:「送付」、0:「送付しない」');
            $table->tinyInteger('direct_mail_flag')->nullable()->default(0)->comment('DMステータス　1:「送付」、0:「送付しない」');
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
            $table->tinyInteger('is_lms_user')->nullable()->default(0);
            $table->string('student_name_kana')->nullable();
            $table->text('student_comment_text')->nullable();
            $table->tinyInteger('in_japan_flag')->nullable()->default(1);
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
