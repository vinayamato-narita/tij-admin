<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_code')->index('project_code');
            $table->string('project_name')->nullable();
            $table->string('project_password')->nullable();
            $table->string('project_real_password', 50)->nullable();
            $table->integer('total_course')->nullable();
            $table->integer('company_id')->nullable()->index('company_id');
            $table->boolean('corporation_flag')->default(0);
            $table->boolean('buy_course_flag')->nullable()->default(1);
            $table->boolean('buy_course_continue')->nullable()->default(0);
            $table->boolean('tsv_import_flag')->nullable()->default(0);
            $table->boolean('payment_type')->nullable()->default(1);
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
        Schema::dropIfExists('lms_projects');
    }
}
