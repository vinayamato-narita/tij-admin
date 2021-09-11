<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserRightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user_rights', function (Blueprint $table) {
            $table->increments('admin_user_rights_id');
            $table->integer('admin_user_id')->nullable();
            $table->integer('admin_rights_id')->nullable();
            $table->boolean('is_permitted')->nullable()->default(0);
            $table->boolean('can_edit')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user_rights');
    }
}
