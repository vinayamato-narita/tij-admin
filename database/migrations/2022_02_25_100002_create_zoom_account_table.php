<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_account', function (Blueprint $table) {
            $table->unsignedInteger('zoom_account_id')->autoIncrement();
            $table->string('zoom_account_name', 255);
            $table->string('api_key', 255);
            $table->string('api_secret', 255);
            $table->string('zoom_user_id', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zoom_account');
    }
}
