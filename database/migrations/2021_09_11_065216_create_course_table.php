<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->increments('course_id')->comment('識別ID');
            $table->string('course_name')->nullable();
            $table->string('course_name_short')->nullable();
            $table->text('course_description')->nullable();
            $table->string('point_count')->comment('付与チケット数');
            $table->unsignedInteger('point_expire_day')->comment('チケット有効日数');
            $table->string('paypal_item_number', 45)->comment('商品コード');
            $table->string('amount', 45)->comment('価格（税抜）');
            $table->unsignedInteger('max_reserve_count')->default(5)->comment('レッスン予約可能最大数');
            $table->integer('is_show')->default(0)->comment('生徒に公開する。0:非公開　1:公開');
            $table->integer('is_campaign')->nullable()->default(0)->comment('キャンペーンコース');
            $table->string('campaign_code', 8)->nullable()->comment('キャンペーンコード, 8桁の英数字');
            $table->unsignedInteger('subscription_count')->nullable()->default(0)->comment('コース購入した回数');
            $table->unsignedInteger('display_order')->nullable()->comment('表示順');
            $table->tinyInteger('is_set_course')->nullable()->default(0)->comment('1: セットコース 0: 単体コース');
            $table->tinyInteger('is_schedule_limit')->nullable()->default(0)->comment('レッスン時間制限。0:制限なし　1:制限あり');
            $table->integer('reserve_start')->nullable()->comment('レッスン予約制限 xx 分前 から');
            $table->integer('reserve_end')->nullable()->default(60)->comment('レッスン予約制限 xx 分前　まで');
            $table->integer('cancel_end')->nullable()->default(720)->comment('レッスンキャンセル制限 xx 分前　まで');
            $table->tinyInteger('is_for_lms')->nullable()->default(0)->comment('LMS表示用コース。0:表示順 1:表示');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course');
    }
}
