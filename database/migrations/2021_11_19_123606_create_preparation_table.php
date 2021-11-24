<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreparationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preparation', function (Blueprint $table) {
            $table->increments('preparation_id');
            $table->integer('display_order');
            $table->string('preparation_name');
            $table->text('preparation_description')->nullable();
            $table->integer('file_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preparation');
    }
}
