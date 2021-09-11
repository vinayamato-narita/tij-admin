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
            $table->increments('admin_rights_id');
            $table->string('admin_rights_name_ja')->nullable();
            $table->string('admin_rights_menu')->nullable();
            $table->unsignedInteger('admin_rights_menu_order');
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
