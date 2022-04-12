<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResetColumnToCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->integer('is_show')->default(1)->nullable()->comment('生徒に公開する。0:非公開　1:公開');
            $table->tinyInteger('is_set_course')->nullable()->default(0)->comment('1: セットコース 0: 単体コース');
            $table->unsignedInteger('point_expire_day')->nullable()->default(0)->comment('チケット有効日数');
            $table->string('campaign_code', 8)->nullable()->comment('キャンペーンコード, 8桁の英数字');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course', function (Blueprint $table) {
            //
        });
    }
}
