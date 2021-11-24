<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('course', function ($table) {
            $table->dropColumn('point_expire_day');
            $table->tinyInteger('course_type')->default(0);
            $table->dropColumn('is_show');
            $table->dropColumn('is_campaign');
            $table->dropColumn('campaign_code');
            $table->dropColumn('subscription_count');
            $table->dropColumn('is_set_course');
            $table->dropColumn('is_schedule_limit');
            $table->dropColumn('reserve_start');
            $table->dropColumn('reserve_end');
            $table->dropColumn('cancel_end');
            $table->dateTime('publish_date_from')->nullable();
            $table->dateTime('publish_date_to')->nullable();
            $table->tinyInteger('expire_day')->default(0)->nullable();
            $table->dateTime('reserve_end_date')->nullable();
            $table->dateTime('decide_date')->nullable();
            $table->dateTime('course_start_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
