<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentPointHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_point_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id')->default(0)->index('student_id');
            $table->dateTime('pay_date');
            $table->text('pay_description')->nullable();
            $table->text('admin_note')->nullable();
            $table->unsignedInteger('pay_type')->default(0);
            $table->decimal('point_count', 6, 1)->default(0.0);
            $table->dateTime('expire_date');
            $table->unsignedInteger('lesson_schedule_id')->index('lesson_schedule_id');
            $table->unsignedInteger('course_id')->default(0);
            $table->dateTime('start_date')->nullable();
            $table->boolean('paid_status')->nullable()->default(1);
            $table->integer('point_subscription_id')->nullable()->default(0)->index('point_id');
            $table->index(['student_id', 'course_id'], 'st_course');
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
        Schema::dropIfExists('student_point_histories');
    }
}
