<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccesslogLoginHistoryOldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesslog_login_history_old', function (Blueprint $table) {
            $table->increments('login_history_id')->comment('識別ID');
            $table->dateTime('login_date')->nullable()->comment('ログイン日時');
            $table->integer('login_user_id')->nullable()->comment('ログインユーザーID');
            $table->string('login_user_name')->nullable()->comment('ユーザー名');
            $table->integer('login_user_type_id')->nullable()->comment('ユーザータイプ');
            $table->string('ipaddress')->nullable()->comment('IPアドレス');
            $table->text('browertype')->nullable()->comment('User agent');
            $table->string('os_name', 100)->nullable()->comment('OS名');
            $table->string('browser_name', 100)->nullable()->comment('ブラウザ名');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accesslog_login_history_old');
    }
}
