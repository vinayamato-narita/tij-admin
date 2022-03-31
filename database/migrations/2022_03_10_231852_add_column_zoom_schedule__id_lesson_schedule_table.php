<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnZoomScheduleIdLessonScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_schedule', function (Blueprint $table) {
            $table->boolean('link_zoom_schedule_flag')->after('zoom_url');
            $table->unsignedInteger('zoom_schedule_id')->nullable()->after('link_zoom_schedule_flag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_schedule', function (Blueprint $table) {
            $table->dropColumn('link_zoom_schedule_flag');
            $table->dropColumn('zoom_schedule_id');
        });
    }
}
