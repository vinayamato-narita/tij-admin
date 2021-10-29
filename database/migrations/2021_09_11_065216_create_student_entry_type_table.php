<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentEntryTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_entry_type', function (Blueprint $table) {
            $table->unsignedInteger('student_entry_type_id')->primary();
            $table->string('student_entry_type_name', 45)->comment('生徒登録状況名。０：利用中、１：仮登録、２：無効');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_entry_type');
    }
}
