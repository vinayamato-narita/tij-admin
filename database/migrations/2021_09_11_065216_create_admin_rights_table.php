<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminRightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_rights', function (Blueprint $table) {
            $table->increments('admin_rights_id')->comment('識別ID');
            $table->string('admin_rights_name_ja')->nullable()->comment('メニュー名');
            $table->string('admin_rights_menu')->nullable()->comment('メニュー記法');
            $table->unsignedInteger('admin_rights_menu_order')->comment('表示順');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_rights');
    }
}
