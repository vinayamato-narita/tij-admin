<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Zoom_Schedule', function (Blueprint $table) {
            $table->unsignedInteger('zoom_schedule_id')->autoIncrement();
            $table->unsignedInteger('zoom_account_id');
            $table->string('zoom_meeting_id', 50);
            $table->tinyInteger('meeting_type')->comment('1:インスタント会議, 2:予定された会議, 3:決まった時間のない定期的な会議, 8:決まった時間での定期的な会議')->nullable();
            $table->dateTime('start_time');
            $table->integer('duration');
            $table->string('time_zone', 255);
            $table->tinyInteger('join_before_host')->default(1)->comment('会議開始前参加可能フラグ。0:Disable, 1:Enable');
            $table->tinyInteger('auto_recording')->comment('自動録画。0:local, 1:cloud, 2:none');
            $table->tinyInteger('waiting_room')->default(0)->comment('待機室有効。0:Disable, 1:Enable');
            $table->string('zoom_url', 255);
            $table->string('password', 255);
            $table->boolean('deleted_flag')->nullable()->comment('0:未削除、1:削除済');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Zoom_Schedule');
    }
}
