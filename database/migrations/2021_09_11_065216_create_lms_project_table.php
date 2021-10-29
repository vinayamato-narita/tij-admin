<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_project', function (Blueprint $table) {
            $table->increments('project_id');
            $table->string('project_code')->index('project_code');
            $table->string('project_name')->nullable();
            $table->string('project_password')->nullable()->comment('法人会員登録画面pass');
            $table->string('project_real_password', 50)->nullable()->comment('法人会員登録画面pass-clear-text');
            $table->integer('total_course')->nullable();
            $table->integer('company_id')->nullable()->index('company_id');
            $table->tinyInteger('corporation_flag')->default(0)->comment('請求先「個人請求」/「法人請求」');
            $table->tinyInteger('buy_course_flag')->nullable()->default(1)->comment('コース購入可」/「コース購入不可');
            $table->tinyInteger('buy_course_continue')->nullable()->default(0)->comment('継続 0:なし　1:あり');
            $table->tinyInteger('tsv_import_flag')->nullable()->default(0)->comment('１：ストライカー連携、０：手動で登録');
            $table->tinyInteger('payment_type')->nullable()->default(1)->comment('支払方法。0:none,1:card, 2: combini, 3: both');
            $table->dateTime('created')->nullable()->comment('作成日時');
            $table->dateTime('modified')->nullable()->comment('更新日時');
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
        Schema::dropIfExists('lms_project');
    }
}
