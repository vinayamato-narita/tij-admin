<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCakeSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cake_sessions', function (Blueprint $table) {
            $table->string('id')->default('')->primary()->comment('セッションID');
            $table->longText('data')->nullable()->comment('セッションデータ');
            $table->integer('expires')->nullable()->comment('有効期限');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cake_sessions');
    }
}
