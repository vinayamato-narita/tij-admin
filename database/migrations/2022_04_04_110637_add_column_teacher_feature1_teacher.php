<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTeacherFeature1Teacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher', function (Blueprint $table) {
            $table->boolean('teacher_feature1')->after('zoom_password')->comment('英語が話せる日本人講師')->nullable();
            $table->boolean('teacher_feature2')->after('teacher_feature1')->comment('キッズ向け')->nullable();
            $table->boolean('teacher_feature3')->after('teacher_feature2')->comment('講師歴3年以上')->nullable();
            $table->boolean('teacher_feature4')->after('teacher_feature3')->comment('日本語能力試験対策')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher', function (Blueprint $table) {
            $table->dropColumn('teacher_feature1');
            $table->dropColumn('teacher_feature2');
            $table->dropColumn('teacher_feature3');
            $table->dropColumn('teacher_feature4');
        });
    }
}
