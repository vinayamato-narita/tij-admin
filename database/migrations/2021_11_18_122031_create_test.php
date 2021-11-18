<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test', function (Blueprint $table) {
            $table->increments('test_id');
            $table->tinyInteger('test_type');
            $table->string('test_name');
            $table->text('test_description')->nullable();
            $table->integer('execution_time')->nullable();
            $table->integer('expire_count')->nullable();
            $table->integer('passing_score');
            $table->integer('total_score');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test');
    }
}
