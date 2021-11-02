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
            $table->increments('admin_user_rights_id')->comment('識別ID');
            $table->integer('admin_user_id')->nullable()->comment('管理ユーザID');
            $table->integer('admin_rights_id')->nullable()->comment('メニューID');
            $table->tinyInteger('is_permitted')->nullable()->default(0)->comment('閲覧可能フラグ');
            $table->tinyInteger('can_edit')->nullable()->default(0)->comment('編集可能フラグ');
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
