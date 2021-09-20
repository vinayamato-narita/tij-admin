<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsProjectAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_project_admins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->index('project_admin');
            $table->integer('admin_id')->index('admin_id');
            $table->integer('admin_progress_show_setting')->nullable()->default(0);
            $table->boolean('tsv_import_flag')->nullable()->default(0);
            $table->boolean('default_flag')->nullable()->default(0);
            $table->boolean('send_mail_flag')->nullable()->default(0);
            $table->boolean('tsv_delete_flag')->nullable()->default(0);
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
        Schema::dropIfExists('lms_project_admins');
    }
}
