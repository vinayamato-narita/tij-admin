<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->tinyInteger('group_lesson_status')->default(0)->comment('0: キャンセル, 1:開催決定, 2：不成立')->after('min_reserve_count');
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
            $table->dropColumn('group_lesson_status');
        });
    }
}
