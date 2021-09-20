<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendRemindMailTimingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_remind_mail_timings', function (Blueprint $table) {
            $table->unsignedInteger('send_remind_mail_timing_type')->primary();
            $table->string('send_remind_mail_timing_type_name', 45);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_remind_mail_timings');
    }
}
