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
        Schema::create('admin_demand_mail', function (Blueprint $table) {
            $table->increments('admin_demand_mail_id')->comment('識別ID');
            $table->string('demand_mail_name')->nullable()->comment('お知らせメール名');
            $table->string('mail_subject')->nullable()->comment('件名');
            $table->text('mail_body')->nullable()->comment('本文');
            $table->smallInteger('target_date_type')->nullable()->default(1)->comment('対象。1:修了日　２：有効期限日');
            $table->integer('send_mail_date')->nullable()->comment('送信時期。 毎月●●日');
            $table->integer('data_summary_range')->nullable()->default(0)->comment('集計期間。　送信時期 xx ヶ月 前/後');
            $table->smallInteger('data_summary_range_direction')->nullable()->default(1)->comment('１: 前　２:後');
            $table->dateTime('created')->nullable()->comment('作成日時');
            $table->dateTime('modified')->nullable()->comment('更新日時');
            $table->smallInteger('public_flag')->nullable()->default(1)->comment('公開区分。1:public, 0: unpublic');
            $table->smallInteger('delete_flag')->nullable()->default(0)->comment('論理削除。1:削除　0:有効');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_demand_mail');
    }
}
