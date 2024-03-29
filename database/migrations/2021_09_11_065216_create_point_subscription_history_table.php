<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointSubscriptionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_subscription_history', function (Blueprint $table) {
            $table->increments('point_subscription_history_id');
            $table->unsignedInteger('student_id')->index('student_id');
            $table->unsignedInteger('course_id')->index('course_id');
            $table->integer('set_course_id')->nullable()->comment('セットコースの場合、セットコードが入られる');
            $table->string('amount', 45);
            $table->string('tax', 45);
            $table->string('currency_unit', 45);
            $table->unsignedTinyInteger('payment_way')->comment('支払い方法：0 GMO, 1 :CSV, 2: Combini');
            $table->dateTime('payment_date')->index('payment_date');
            $table->unsignedTinyInteger('payment_status')->comment('0: not complete, 1 completed');
            $table->string('order_id', 27)->nullable();
            $table->dateTime('update_date');
            $table->unsignedTinyInteger('del_flag')->default(0);
            $table->string('item_name')->nullable();
            $table->string('point_count', 45)->nullable();
            $table->string('customer_code')->nullable();
            $table->dateTime('begin_date')->nullable();
            $table->dateTime('point_expire_date')->nullable();
            $table->string('course_code')->nullable();
            $table->string('corporation_code')->nullable();
            $table->dateTime('receive_payment_date')->nullable();
            $table->tinyInteger('paid_status')->nullable()->default(1)->comment('コンビニ決済の支払状況, 1:paid, 0: not yet');
            $table->string('management_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_subscription_history');
    }
}
