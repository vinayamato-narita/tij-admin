<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('default_project_code', 50)->nullable()->default(null);
            $table->string('admin_email')->nullable();
            $table->string('admin_password')->nullable();
            $table->string('admin_real_password')->nullable();
            $table->string('admin_name')->nullable();
            $table->string('admin_name_kana')->nullable();
            $table->string('admin_department')->nullable();
            $table->string('admin_phone_number')->nullable();
            $table->string('admin_destination_name')->nullable();
            $table->string('branch_office')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->integer('prefecture_number')->nullable();
            $table->string('address1', 500)->nullable();
            $table->string('address2', 500)->nullable();
            $table->string('address3', 500)->nullable();
            $table->string('address4', 500)->nullable();
            $table->boolean('super_admin_flag')->nullable()->default(0);
            $table->boolean('type')->nullable()->default(1);
            $table->string('security_string')->nullable();
            $table->dateTime('init_pass_expire_datetime')->nullable();
            $table->dateTime('last_change_password_time')->nullable();
            $table->dateTime('last_login_datetime')->nullable();
            $table->string('last_login_ip', 50)->nullable();
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
        Schema::dropIfExists('lms_admins');
    }
}
