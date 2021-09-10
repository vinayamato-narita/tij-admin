<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher', function (Blueprint $table) {
            $table->increments('teacher_id');
            $table->string('teacher_name');
            $table->string('teacher_email');
            $table->string('teacher_password');
            $table->string('teacher_nickname');
            $table->dateTime('teacher_birthday');
            $table->string('teacher_skypename')->nullable();
            $table->boolean('teacher_sex')->nullable()->default(1);
            $table->text('teacher_introduction');
            $table->text('teacher_note');
            $table->text('photo_savepath')->nullable();
            $table->text('movie_savepath')->nullable();
            $table->text('sound_savepath')->nullable();
            $table->dateTime('last_login_date')->nullable();
            $table->string('is_sercurity')->default('0 COMMENT 'パスワード忘れセキュリティストリング'');
            $table->string('teacher_university');
            $table->string('teacher_department');
            $table->string('teacher_hobby')->index('japanese');
            $table->string('sound_showname')->nullable();
            $table->string('movie_showname')->nullable();
            $table->text('introduce_from_admin')->nullable();
            $table->tinyInteger('brand_id');
            $table->string('payment_des_code', 45)->nullable();
            $table->boolean('show_flag')->nullable()->default(1);
            $table->boolean('is_free_teacher')->nullable()->default(0);
            $table->integer('display_order')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher');
    }
}
