<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user', function (Blueprint $table) {
            $table->increments('admin_user_id')->comment('識別ID');
            $table->string('admin_user_name')->nullable();
            $table->string('admin_user_email')->nullable();
            $table->string('password')->nullable();
            $table->text('admin_user_description')->nullable();
            $table->dateTime('last_login_date')->nullable();
            $table->string('remember_token')->nullable();
            $table->dateTime('remember_token_expires_at')->nullable();
            $table->smallInteger('is_join_contact')->nullable()->default(0)->comment('利用してない');
            $table->smallInteger('is_online')->nullable()->default(0)->comment('緊急連絡先に含める。0:含まない　1:含む');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user');
    }
}
