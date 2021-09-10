<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqCategoryInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq_category_info', function (Blueprint $table) {
            $table->integer('faq_category_info_id')->primary();
            $table->integer('faq_category_id')->nullable();
            $table->string('faq_category_name')->nullable();
            $table->string('lang_type', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_category_info');
    }
}
