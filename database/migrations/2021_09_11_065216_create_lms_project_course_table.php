<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsProjectCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_project_course', function (Blueprint $table) {
            $table->increments('project_course_id');
            $table->integer('company_id')->nullable();
            $table->integer('project_id')->index('project_id');
            $table->integer('course_id')->index('course_id');
            $table->integer('set_course_id')->nullable()->comment('セットコースのコースの場合、セットコード入れる');
            $table->integer('parent_id')->nullable()->comment('セットコースの子コースの場合、父project_course_idを入れる');
            $table->smallInteger('course_type')->nullable()->default(0)->comment('普通コース：0、セットコース :1、セットコースの子コース：２');
            $table->string('course_code', 15)->nullable();
            $table->float('price', 10, 0)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('bill_address')->nullable();
            $table->float('complete_require', 10, 2)->nullable()->default(0.00);
            $table->smallInteger('complete_require_marks')->nullable()->default(0);
            $table->smallInteger('complete_require_type')->nullable()->default(1)->comment('1:出欠、２：点数（平均）、３：点数（各テスト）');
            $table->tinyInteger('complete_certificate_flag')->nullable()->default(0);
            $table->dateTime('start_date')->nullable()->comment('申し込み可能期間Start');
            $table->dateTime('expired_date')->nullable()->comment('申し込み可能期間Expired');
            $table->integer('max_student_num')->nullable()->default(0);
            $table->string('education_corporation_order_number', 12)->nullable();
            $table->tinyInteger('tsv_import_flag')->nullable()->default(0);
            $table->tinyInteger('score_grace_period')->nullable()->default(0);
            $table->tinyInteger('start_date_option')->nullable()->default(1)->comment('１．セット商品（前月20日）２．開講月1日開始　３．即時');
            $table->tinyInteger('certificate_download_target')->nullable()->default(1)->comment('１．担当者宛 ２．受講生本人宛 3.両方');
            $table->integer('order_number')->nullable()->default(0);
            $table->tinyInteger('show_flag')->nullable()->default(1)->comment('1:生徒マイページで表示され');
            $table->dateTime('created')->nullable()->comment('作成日時');
            $table->dateTime('modified')->nullable()->comment('更新日時');
            $table->smallInteger('delete_flag')->nullable()->default(0)->comment('論理削除。1:削除　0:有効');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_project_course');
    }
}
