<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_setting', function (Blueprint $table) {
            $table->increments('system_setting_id');
            $table->unsignedInteger('student_lesson_reservable_day');
            $table->unsignedInteger('teacher_lesson_applicatinable_day');
            $table->unsignedInteger('admin_lesson_creatable_day');
            $table->unsignedInteger('lesson_end_time');
            $table->unsignedInteger('lesson_just_before_cancel_time');
            $table->string('lesson_just_before_cancel_point', 45);
            $table->unsignedInteger('lesson_just_before_register_time');
            $table->string('lesson_just_before_register_point', 45);
            $table->unsignedInteger('tmp_student_expire_time')->default(24);
            $table->unsignedInteger('free_lesson_view_just_before_time_for_free_student');
            $table->unsignedInteger('free_lesson_view_just_before_time_for_pay_student');
            $table->unsignedInteger('teacher_rating_for_free_lesson');
            $table->integer('min_teacher_point')->nullable();
            $table->unsignedInteger('timezone_id')->default(1);
            $table->unsignedInteger('before_day_x')->default(0);
            $table->unsignedInteger('before_day_y')->default(0);
            $table->unsignedInteger('before_day_z')->default(0);
            $table->unsignedInteger('lesson_just_before_cancel_time_lost100perPoint');
            $table->integer('lesson_just_start_booking_time')->nullable()->default(0)->comment('time before can booking lesson');
            $table->integer('free_teacher_lesson_register_time')->nullable()->default(8)->comment('XX週間前から登録可');
            $table->integer('free_teacher_lesson_cancel_time')->nullable()->default(60)->comment('XX分前まで削除可');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_setting');
    }
}
