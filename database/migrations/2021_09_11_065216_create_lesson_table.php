<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson', function (Blueprint $table) {
            $table->increments('lesson_id');
            $table->string('lesson_name')->nullable();
            $table->text('lesson_description')->nullable();
            $table->integer('display_order')->default(1);
            $table->smallInteger('is_test_lesson')->nullable()->default(0)->comment('テストあり・なし。１：テストあり　０：テストなし');
            $table->smallInteger('is_show_to_search')->nullable()->default(1)->comment('サジェスト検索に含める。１：含める　０：含めない');
            $table->smallInteger('is_show_to_teacher_detail')->nullable()->default(1)->comment('講師プロフィールに表示。１：表示する　０：表示しない');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson');
    }
}
