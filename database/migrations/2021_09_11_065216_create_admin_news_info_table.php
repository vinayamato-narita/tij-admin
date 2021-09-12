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
            $table->integer('admin_news_info_id')->primary();
            $table->integer('news_id');
            $table->string('news_title')->nullable();
            $table->text('news_body')->nullable();
            $table->string('lang_type', 10)->nullable();
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
