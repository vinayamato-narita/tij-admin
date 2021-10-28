<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_info', function (Blueprint $table) {
            $table->increments('category_info_id')->comment('識別ID');
            $table->integer('category_id');
            $table->string('category_name')->nullable();
            $table->string('lang_type', 10)->comment('言語タイプ。en:英語　vn:ベトナム語');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_info');
    }
}
