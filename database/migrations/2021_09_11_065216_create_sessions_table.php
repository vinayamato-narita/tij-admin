<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->char('id', 40)->nullable()->primary();
            $table->dateTime('created')->nullable()->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->dateTime('modified')->nullable()->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->longText('data')->nullable();
            $table->unsignedInteger('expires')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
