<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country', function (Blueprint $table) {
            $table->increments('country_id')->comment('識別ID');
            $table->string('country_name')->nullable();
            $table->string('country_name_jp')->nullable();
            $table->string('abbreviation')->nullable();
        });

        Schema::table('student', function (Blueprint $table) {
            $table->integer('country_id')->nullable();
            $table->string('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country');
    }
}
