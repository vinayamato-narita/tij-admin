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
        Schema::create('admin_inquiries', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('inquiry_date');
            $table->string('inquiry_subject');
            $table->text('inquiry_body');
            $table->integer('inquiry_flag');
            $table->text('inquiry_memo');
            $table->unsignedInteger('student_type');
            $table->unsignedInteger('student_id');
            $table->string('student_email')->nullable();
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
        Schema::dropIfExists('admin_inquiries');
    }
}
