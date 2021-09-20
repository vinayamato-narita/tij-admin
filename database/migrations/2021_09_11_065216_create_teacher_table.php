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
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('teacher_name');
            $table->string('teacher_email');
            $table->string('password');
            $table->string('teacher_nickname');
            $table->dateTime('teacher_birthday')->nullable();
            $table->string('teacher_skypename')->nullable();
            $table->boolean('teacher_sex')->nullable()->default(1);
            $table->text('teacher_introduction')->nullable();
            $table->text('teacher_note')->nullable();
            $table->text('photo_savepath')->nullable();
            $table->text('movie_savepath')->nullable();
            $table->text('sound_savepath')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->dateTime('remember_token_expires_at')->nullable();
            $table->string('teacher_university');
            $table->string('teacher_department');
            $table->string('teacher_hobby')->index('japanese');
            $table->string('sound_showname')->nullable();
            $table->string('movie_showname')->nullable();
            $table->text('introduce_from_admin')->nullable();
            $table->string('payment_des_code', 45)->nullable();
            $table->boolean('show_flag')->nullable()->default(1);
            $table->boolean('is_free_teacher')->nullable()->default(0);
            $table->integer('display_order')->nullable()->default(1);
            $table->tinyInteger('timezone_id');
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
        Schema::dropIfExists('teacher');
    }
}
