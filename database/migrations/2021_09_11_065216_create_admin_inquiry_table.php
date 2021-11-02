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
            $table->increments('inquiry_id')->comment('識別ID');
            $table->dateTime('inquiry_date')->comment('問い合わせ日時');
            $table->string('inquiry_subject')->comment('問い合わせ件名');
            $table->text('inquiry_body')->comment('内容');
            $table->integer('inquiry_flag')->comment('対応状況。0:未対応 1:対応済');
            $table->text('inquiry_memo');
            $table->unsignedInteger('user_type')->comment('ユーザのタイプを格納。0:student,1:teacher');
            $table->unsignedInteger('user_id')->comment('ユーザーのIDを格納。student_id or teacher_id');
            $table->string('user_mail')->nullable()->comment('メールアドレス');
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
