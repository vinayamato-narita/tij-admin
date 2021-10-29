<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGmoMaintainSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmo_maintain_setting', function (Blueprint $table) {
            $table->increments('gmo_maintain_setting_id');
            $table->unsignedTinyInteger('gmo_payment_type')->default(1)->comment('決済方法,１：コンビニ決済、２：カード決済、３：両方');
            $table->unsignedTinyInteger('cvs_type')->default(1)->comment('コンビニ種類、１：セブンイレブン、２：セブンイレブン以外、３：全部');
            $table->dateTime('maintain_time_from')->nullable()->comment('メンテナンス時間から');
            $table->dateTime('maintain_time_to')->nullable()->comment('メンテナンス時間まで');
            $table->text('maintain_text')->nullable()->comment('メンテナンス文言');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gmo_maintain_setting');
    }
}
