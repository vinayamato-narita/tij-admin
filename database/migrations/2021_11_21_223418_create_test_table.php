<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTable extends Migration
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
            $table->integer('test_type');
            $table->string('test_name');
            $table->text('test_description')->nullable();
            $table->unsignedInteger ('execution_time')->nullable();
            $table->unsignedInteger ('expire_count')->nullable();
            $table->unsignedInteger ('passing_score');
            $table->unsignedInteger ('total_score');
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
