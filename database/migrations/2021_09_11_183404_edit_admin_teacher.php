<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditAdminTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_user', function (Blueprint $table) {
            $table->dropColumn('brand_id');
            $table->timestamp('last_login_at')->nullable();
            $table->renameColumn('admin_user_id', 'id');

        });
        Schema::table('teacher', function (Blueprint $table) {
            $table->renameColumn('teacher_id', 'id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
