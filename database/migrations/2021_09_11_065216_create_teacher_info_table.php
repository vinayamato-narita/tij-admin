<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('teacher_id');
            $table->string('teacher_name')->nullable();
            $table->string('teacher_nickname')->nullable();
            $table->text('teacher_introduction')->nullable();
            $table->text('introduce_from_admin')->nullable();
            $table->string('teacher_university')->nullable();
            $table->string('teacher_department')->nullable();
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
        Schema::dropIfExists('teacher_infos');
    }
}
