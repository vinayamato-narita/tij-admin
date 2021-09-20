<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->string('order_id', 27)->primary();
            $table->unsignedInteger('student_id')->default(0);
            $table->bigInteger('student_card_id')->nullable();
            $table->unsignedInteger('course_id')->default(0);
            $table->string('product_code', 45);
            $table->string('campaign_code', 45)->nullable();
            $table->dateTime('order_date');
            $table->unsignedTinyInteger('order_status')->default(0);
            $table->string('order_ip', 100)->nullable();
            $table->string('gmo_error_code', 20)->nullable();
            $table->unsignedTinyInteger('error_step')->nullable()->default(0);
            $table->string('corporation_code', 45)->nullable();
            $table->string('access_id', 50)->nullable();
            $table->string('transaction_id', 50)->nullable();
            $table->string('cvs_code', 50)->nullable();
            $table->string('cvs_conf_no', 50)->nullable();
            $table->string('cvs_receipt_no', 50)->nullable();
            $table->dateTime('payment_term')->nullable();
            $table->index(['student_id', 'order_id'], 'student_order');
            $table->index(['order_id', 'course_id'], 'course_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
