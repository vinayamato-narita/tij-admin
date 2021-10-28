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
            $table->increments('project_admin_id');
            $table->integer('project_id')->index('project_admin');
            $table->integer('admin_id')->index('admin_id');
            $table->integer('admin_progress_show_setting')->nullable()->default(0)->comment('送付先用　、　例）有効期限30日過ぎたデータは成績確認画面には表示させない。');
            $table->smallInteger('tsv_import_flag')->nullable()->default(0)->comment('１：ストライカー連携　０：手動で登録');
            $table->smallInteger('default_flag')->nullable()->default(0)->comment('企業の送付先デフォルトフラグ。１：デフォルト　０：デフォルトでない');
            $table->smallInteger('send_mail_flag')->nullable()->default(0)->comment('お知らせメール送付可・不可。０：不可　１：可');
            $table->smallInteger('tsv_delete_flag')->nullable()->default(0)->comment('ストライカーから削除フラグ');
            $table->dateTime('created')->nullable()->comment('作成日時');
            $table->dateTime('modified')->nullable()->comment('更新日時');
            $table->smallInteger('delete_flag')->nullable()->default(0)->comment('削除フラグ');
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
