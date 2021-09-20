<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminDemandMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_demand_mails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('demand_mail_name')->nullable();
            $table->string('mail_subject')->nullable();
            $table->text('mail_body')->nullable();
            $table->smallInteger('target_date_type')->nullable()->default(1);
            $table->integer('send_mail_date')->nullable();
            $table->integer('data_summary_range')->nullable()->default(0);
            $table->smallInteger('data_summary_range_direction')->nullable()->default(1);
            $table->boolean('public_flag')->nullable()->default(1);
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
        Schema::dropIfExists('admin_demand_mails');
    }
}
