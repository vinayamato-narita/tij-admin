<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableTestTopScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_top_score', function (Blueprint $table) {
            $table->unsignedInteger('test_top_score_id')->autoIncrement();
            $table->unsignedInteger('test_id');
            $table->string('test_parrent_name')->nullable();
            $table->double('top_score_avg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
