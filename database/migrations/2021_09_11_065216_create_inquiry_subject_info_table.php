<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquirySubjectInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_subject_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inquiry_subject_id');
            $table->string('inquiry_subject')->nullable();
            $table->string('lang_type', 10);
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
        Schema::dropIfExists('inquiry_subject_infos');
    }
}
