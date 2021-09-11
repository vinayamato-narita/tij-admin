<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq_info', function (Blueprint $table) {
            $table->integer('faq_info_id')->primary();
            $table->integer('faq_id');
            $table->string('question')->nullable();
            $table->text('answer')->nullable();
            $table->string('lang_type', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_info');
    }
}
