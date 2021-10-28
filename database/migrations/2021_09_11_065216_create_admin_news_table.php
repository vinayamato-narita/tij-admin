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
            $table->increments('news_id')->comment('識別ID');
            $table->string('news_title', 500);
            $table->text('news_body');
            $table->integer('news_subject_id');
            $table->dateTime('news_update_date');
            $table->tinyInteger('is_show_on_student_top')->default(0)->comment('トップ表示。1:表示 0: 非表示');
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
