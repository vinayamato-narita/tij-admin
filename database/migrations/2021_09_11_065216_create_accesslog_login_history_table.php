<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccesslogLoginHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesslog_login_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('login_at')->nullable();
            $table->integer('login_user_id')->nullable();
            $table->string('login_user_name')->nullable();
            $table->integer('accesslog_user_type_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('brower_type')->nullable();
            $table->string('os_name', 100)->nullable();
            $table->string('browser_name', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accesslog_login_histories');
    }
}
