<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTestTopScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('test_top_score', function (Blueprint $table) {
        $table->integer('test_category_id')->nullable()->after('test_parrent_name');
        $table->string('category_name',255)->after('test_category_id')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('test_top_score', function (Blueprint $table) {
        $table->dropColumn('test_category_id');
        $table->dropColumn('category_name');
    });
    }
}
