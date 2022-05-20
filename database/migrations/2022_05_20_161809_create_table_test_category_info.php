<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTestCategoryInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('test_category_info', function (Blueprint $table) {
            $table->integer('test_category_info_id')->autoIncrement();
            $table->integer('test_category_id');
            $table->string('parent_category_name');
            $table->string('category_name');
            $table->string('lang_type');
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_test_category_info');
    }
}
