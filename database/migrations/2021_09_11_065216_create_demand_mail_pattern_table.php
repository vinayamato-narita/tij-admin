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
            $table->increments('demand_mail_pattern_id')->comment('識別ID');
            $table->string('demand_mail_name')->nullable();
            $table->string('mail_subject')->nullable();
            $table->text('mail_body')->nullable();
            $table->integer('target_date_type')->nullable()->default(2)->comment('対象期日。2:受講開始日　3：　有効期限日');
            $table->integer('target_date_number')->nullable()->default(0)->comment('日数');
            $table->tinyInteger('target_date_direction')->nullable()->default(1)->comment('１: 前　２:後');
            $table->tinyInteger('lesson_counter_condition_active')->nullable()->default(0)->comment('受講状況条件Activeフラグ。0:not active, 1: active');
            $table->integer('lesson_counter_condition_type')->nullable()->default(1)->comment('受講状況条件タイプ。1:回数 2:割合');
            $table->integer('lesson_counter_condition_target')->nullable()->default(1)->comment('受講状況条件対象。1:受講回数 2:予約回数');
            $table->integer('lesson_counter_condition_number')->nullable()->default(0)->comment('受講状況条件。回数');
            $table->tinyInteger('lesson_counter_condition_direction')->nullable()->default(1)->comment('受講状況条件。1:以上　２：未満');
            $table->tinyInteger('target_user_type_active')->nullable()->default(0)->comment('生徒区分条件Activeフラグ。0:not active, 1: active');
            $table->string('target_user_type', 50)->nullable()->default('1,2,3,4')->comment('生徒区分条件対象。1 無料会員(一般）、2 無料会員(法人）、3 有料会員(一般)、4 有料会員(法人)');
            $table->tinyInteger('project_condition_active')->nullable()->default(0)->comment('企業IDの条件Activeフラグ。0:not active, 1: active');
            $table->integer('project_id_condition')->nullable()->comment('企業IDの条件。対象企業ID');
            $table->tinyInteger('public_flag')->nullable()->default(1)->comment('公開フラグ。0:非公開　1:公開');
            $table->tinyInteger('delete_flag')->nullable()->default(0)->comment('削除フラグ');
            $table->dateTime('created')->nullable()->comment('作成日時');
            $table->dateTime('modified')->nullable()->comment('更新日時');
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
