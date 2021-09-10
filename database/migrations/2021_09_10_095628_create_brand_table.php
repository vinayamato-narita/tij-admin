<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand', function (Blueprint $table) {
            $table->integer('brand_id')->primary();
            $table->string('brand_name')->default(' COMMENT 'ブランド名'');
            $table->string('display_brand_name')->nullable();
            $table->string('mail_domain');
            $table->string('mail_from_name')->nullable();
            $table->text('url');
            $table->text('confirm_url')->nullable();
            $table->text('mail_reply')->nullable();
            $table->text('default_studet_image')->nullable();
            $table->integer('course_free_id')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand');
    }
}
