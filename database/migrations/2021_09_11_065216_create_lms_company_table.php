<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_code', 8)->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_name_kana')->nullable();
            $table->integer('prefecture_number')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('employe_number')->nullable();
            $table->dateTime('desired_start_time')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('address1', 500)->nullable();
            $table->string('address2', 500)->nullable();
            $table->string('address3', 500)->nullable();
            $table->string('address4', 500)->nullable();
            $table->string('payment_name', 500)->nullable();
            $table->string('head_office', 500)->nullable();
            $table->string('represent_person_name')->nullable();
            $table->string('represent_person_name_kana')->nullable();
            $table->string('represent_person_department')->nullable();
            $table->string('represent_person_phone')->nullable();
            $table->string('represent_person_email')->nullable();
            $table->string('company_tel', 20)->nullable();
            $table->string('home_tel', 20)->nullable();
            $table->string('company_lecture_code')->nullable();
            $table->integer('exported_object')->nullable();
            $table->string('classify_code')->nullable();
            $table->string('company_group_code')->nullable();
            $table->string('legal_code')->nullable();
            $table->string('favourite_code')->nullable();
            $table->string('bill_address')->nullable();
            $table->string('common_mgt_no')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('lms_companies');
    }
}
