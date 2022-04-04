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
            $table->boolean('teacher_feature1')->after('zoom_password')->nullable();
            $table->boolean('teacher_feature2')->after('teacher_feature1')->nullable();
            $table->boolean('teacher_feature3')->after('teacher_feature2')->nullable();
            $table->boolean('teacher_feature4')->after('teacher_feature3')->nullable();
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
