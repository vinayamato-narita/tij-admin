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
            $table->increments('faq_info_id');
            $table->integer('faq_id');
            $table->string('question')->nullable();
            $table->text('answer')->nullable();
            $table->string('lang_type', 10)->comment('言語タイプ。en:英語　vn:ベトナム語');
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
