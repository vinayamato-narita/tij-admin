<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('course_map');
        Schema::drop('course_schedule_setting');
        Schema::drop('course_set_course');
        Schema::drop('course_video');
        Schema::drop('gmo_maintain_setting');
        Schema::drop('lms_admin');
        Schema::drop('lms_admin_login_history');
        Schema::drop('lms_bill_address');
        Schema::drop('lms_company');
        Schema::drop('lms_prefecture');
        Schema::drop('lms_project');
        Schema::drop('lms_project_admin');
        Schema::drop('lms_project_course');
        Schema::drop('lms_project_course_student');
        Schema::drop('lms_project_demandmail');
        Schema::drop('lms_project_student');
        Schema::drop('lms_system_setting');
        Schema::drop('payment_status');
        Schema::drop('payment_type');
        Schema::drop('plusid');
        Schema::drop('sessions');
        Schema::drop('show_status');
        Schema::drop('system_buttons_timing');
        Schema::drop('system_setting');
        Schema::drop('text_map');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
