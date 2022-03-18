<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFaqCategoryAddParent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq_parent_category', function (Blueprint $table) {
            $table->increments('faq_parent_category_id');
            $table->string('faq_parent_category_name');
        });

        Schema::create('faq_parent_category_info', function (Blueprint $table) {
            $table->increments('faq_parent_category_info_id');
            $table->bigInteger('faq_parent_category_id');
            $table->string('faq_parent_category_name');
            $table->string('lang_type');
        });

        Schema::table('faq_category', function (Blueprint $table) {
            $table->bigInteger('faq_parent_category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
