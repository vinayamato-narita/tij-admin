<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_campaign', function (Blueprint $table) {
            $table->increments('course_campaign_id');
            $table->integer('course_id');
            $table->float('price', 10, 0)->nullable();
            $table->dateTime('campaign_start_time');
            $table->dateTime('campaign_end_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_campain');
    }
}
