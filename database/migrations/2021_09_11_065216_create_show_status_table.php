<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_status', function (Blueprint $table) {
            $table->integer('show_status')->primary();
            $table->string('show_status_name_ja')->default('');
            $table->string('show_status_name_en')->default('');
            $table->string('show_status_name_vn')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('show_status');
    }
}
