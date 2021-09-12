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
            $table->increments('admin_user_id');
            $table->string('admin_user_name')->nullable();
            $table->string('admin_user_email')->nullable();
            $table->string('admin_user_password')->nullable();
            $table->text('admin_user_description')->nullable();
            $table->dateTime('last_login_date')->nullable();
            $table->string('is_sercurity')->default('0');
            $table->unsignedTinyInteger('is_join_contact')->nullable()->default(0);
            $table->unsignedTinyInteger('is_online')->nullable()->default(0);
            $table->tinyInteger('brand_id');
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
