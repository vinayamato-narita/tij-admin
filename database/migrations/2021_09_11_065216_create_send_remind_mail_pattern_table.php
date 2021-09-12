<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendRemindMailPatternTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_remind_mail_pattern', function (Blueprint $table) {
            $table->increments('send_remind_mail_pattern_id');
            $table->unsignedInteger('send_remind_mail_timing_type');
            $table->unsignedInteger('timing_minutes');
            $table->text('mail_subject');
            $table->text('mail_body');
            $table->tinyInteger('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_remind_mail_pattern');
    }
}
