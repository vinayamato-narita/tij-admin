<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemButtonsTimingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_buttons_timing', function (Blueprint $table) {
            $table->unsignedInteger('button_id');
            $table->tinyInteger('brand_id');
            $table->string('start_time', 50)->nullable();
            $table->string('end_time', 50)->nullable();
            $table->primary(['button_id', 'brand_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_buttons_timing');
    }
}
