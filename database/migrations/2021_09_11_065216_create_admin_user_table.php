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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('admin_name')->nullable();
            $table->string('admin_email')->nullable();
            $table->string('password')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->dateTime('remember_token_expires_at')->nullable();
            $table->boolean('is_join_contact')->nullable()->default(0);
            $table->boolean('is_online')->nullable()->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
