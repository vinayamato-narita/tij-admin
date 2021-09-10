<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_news', function (Blueprint $table) {
            $table->increments('news_id');
            $table->string('news_title', 500);
            $table->text('news_body');
            $table->integer('news_subject_id');
            $table->dateTime('news_update_date');
            $table->tinyInteger('brand_id');
            $table->unsignedTinyInteger('is_show_on_student_top')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_news');
    }
}
