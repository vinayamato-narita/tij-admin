<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminNewsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_news_info', function (Blueprint $table) {
            $table->increments('admin_news_info_id')->comment('識別ID');
            $table->integer('news_id');
            $table->string('news_title')->nullable();
            $table->text('news_body')->nullable();
            $table->string('lang_type', 10)->nullable()->comment('言語タイプ。en:英語　vn:ベトナム語');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_news_info');
    }
}
