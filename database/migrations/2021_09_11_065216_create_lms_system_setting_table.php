<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsSystemSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_system_setting', function (Blueprint $table) {
            $table->increments('system_setting_id');
            $table->integer('admin_progress_show_setting')->nullable()->default(0)->comment('例）有効期限30日過ぎたデータは成績確認画面には表示させない。');
            $table->integer('observer_progress_show_setting')->nullable()->default(0)->comment('営業担当者用　、　例）有効期限30日過ぎたデータは成績確認画面には表示させない。');
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
        Schema::dropIfExists('lms_system_setting');
    }
}
