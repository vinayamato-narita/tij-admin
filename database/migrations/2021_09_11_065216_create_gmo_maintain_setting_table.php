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
        Schema::create('gmo_maintain_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('gmo_payment_type')->default(1);
            $table->unsignedTinyInteger('cvs_type')->default(1);
            $table->dateTime('maintain_time_from')->nullable();
            $table->dateTime('maintain_time_to')->nullable();
            $table->text('maintain_text')->nullable();
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
        Schema::dropIfExists('gmo_maintain_settings');
    }
}
