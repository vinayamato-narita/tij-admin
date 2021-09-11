<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_inquiry', function (Blueprint $table) {
            $table->increments('inquiry_id');
            $table->dateTime('inquiry_date');
            $table->string('inquiry_subject');
            $table->text('inquiry_body');
            $table->integer('inquiry_flag');
            $table->text('inquiry_memo');
            $table->unsignedInteger('user_type');
            $table->unsignedInteger('user_id');
            $table->string('user_mail')->nullable();
            $table->tinyInteger('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_inquiry');
    }
}
