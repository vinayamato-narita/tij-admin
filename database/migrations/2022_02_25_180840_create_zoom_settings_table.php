<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_setting', function (Blueprint $table) {
            $table->increments('zoom_setting_id');
            $table->tinyInteger('join_before_host')->default(1)->comment('会議開始前参加可能フラグ。0:Disable, 1:Enable');
            $table->tinyInteger('auto_recording')->comment('自動録画。0:local, 1:cloud, 2:none');
            $table->tinyInteger('waiting_room')->default(0)->comment('待機室有効。0:Disable, 1:Enable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zoom_setting');
    }
}
