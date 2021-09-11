<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsBillAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_bill_address', function (Blueprint $table) {
            $table->integer('bill_address_id')->primary();
            $table->string('bill_code', 225);
            $table->string('bill_address');
            $table->dateTime('created')->nullable();
            $table->dateTime('modified')->nullable();
            $table->boolean('delete_flag')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_bill_address');
    }
}
