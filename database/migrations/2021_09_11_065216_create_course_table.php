<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('course_id');
            $table->string('course_name')->nullable();
            $table->string('course_name_short')->nullable();
            $table->text('course_description')->nullable();
            $table->string('point_count');
            $table->unsignedInteger('point_expire_day');
            $table->string('paypal_item_number', 45);
            $table->string('amount', 45);
            $table->unsignedInteger('max_reserve_count')->default(5);
            $table->integer('is_show')->default(0);
            $table->integer('is_campaign')->nullable()->default(0);
            $table->string('campaign_code', 8)->nullable();
            $table->unsignedInteger('subscription_count')->nullable()->default(0);
            $table->unsignedInteger('display_order')->nullable();
            $table->boolean('is_set_course')->nullable()->default(0);
            $table->boolean('is_schedule_limit')->nullable()->default(0);
            $table->integer('reserve_start')->nullable();
            $table->integer('reserve_end')->nullable()->default(60);
            $table->integer('cancel_end')->nullable()->default(720);
            $table->boolean('is_for_lms')->nullable()->default(0);
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
        Schema::dropIfExists('courses');
    }
}
