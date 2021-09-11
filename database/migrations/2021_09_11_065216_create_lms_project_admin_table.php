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
        Schema::create('lms_project_admin', function (Blueprint $table) {
            $table->integer('project_admin_id')->primary();
            $table->integer('project_id')->index('project_admin');
            $table->integer('admin_id')->index('admin_id');
            $table->integer('admin_progress_show_setting')->nullable()->default(0);
            $table->dateTime('created')->nullable();
            $table->dateTime('modified')->nullable();
            $table->boolean('tsv_import_flag')->nullable()->default(0);
            $table->boolean('default_flag')->nullable()->default(0);
            $table->boolean('send_mail_flag')->nullable()->default(0);
            $table->boolean('delete_flag')->nullable()->default(0)->index('delete_flag');
            $table->boolean('tsv_delete_flag')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_project_admin');
    }
}
