<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemandMailPatternInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand_mail_pattern_info', function (Blueprint $table) {
            $table->integer('demand_mail_pattern_info_id')->primary();
            $table->integer('demand_mail_pattern_id');
            $table->string('mail_subject')->nullable();
            $table->text('mail_body')->nullable();
            $table->string('lang_type', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demand_mail_pattern_info');
    }
}
