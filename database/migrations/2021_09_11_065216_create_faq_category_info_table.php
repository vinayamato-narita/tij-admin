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
        Schema::create('faq_category_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('faq_category_id')->nullable();
            $table->string('faq_category_name')->nullable();
            $table->string('lang_type', 10)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_category_infos');
    }
}
