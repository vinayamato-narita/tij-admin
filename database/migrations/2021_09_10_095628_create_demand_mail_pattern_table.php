<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemandMailPatternTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand_mail_pattern', function (Blueprint $table) {
            $table->integer('demand_mail_pattern_id')->primary();
            $table->string('demand_mail_name')->nullable();
            $table->string('mail_subject')->nullable();
            $table->text('mail_body')->nullable();
            $table->integer('target_date_type')->nullable()->default(2);
            $table->integer('target_date_number')->nullable()->default(0);
            $table->boolean('target_date_direction')->nullable()->default(1);
            $table->boolean('lesson_counter_condition_active')->nullable()->default(0);
            $table->integer('lesson_counter_condition_type')->nullable()->default(1);
            $table->integer('lesson_counter_condition_target')->nullable()->default(1);
            $table->integer('lesson_counter_condition_number')->nullable()->default(0);
            $table->boolean('lesson_counter_condition_direction')->nullable()->default(1);
            $table->boolean('target_user_type_active')->nullable()->default(0);
            $table->string('target_user_type', 50)->nullable()->default('1,2,3,4 COMMENT '生徒区分条件対象。1');
            $table->boolean('project_condition_active')->nullable()->default(0);
            $table->integer('project_id_condition')->nullable();
            $table->dateTime('created')->nullable();
            $table->dateTime('modified')->nullable();
            $table->boolean('public_flag')->nullable()->default(1);
            $table->boolean('delete_flag')->nullable()->default(0);
            $table->tinyInteger('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demand_mail_pattern');
    }
}
