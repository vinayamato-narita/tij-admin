<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsAdminLoginHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_admin_login_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->dateTime('login_date')->nullable();
            $table->string('login_ip')->nullable();
            $table->string('browertype')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_admin_login_histories');
    }
}
