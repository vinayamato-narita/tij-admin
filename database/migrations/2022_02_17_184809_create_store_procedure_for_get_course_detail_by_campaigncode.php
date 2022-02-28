<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForGetCourseDetailByCampaigncode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_course_detail_by_campaigncode`;
        CREATE PROCEDURE `sp_get_course_detail_by_campaigncode`(
        IN _course_id INT,
        IN _lang_type VARCHAR(10))
        BEGIN
            SELECT course.course_id,
                COALESCE(ci.course_name, course.course_name) as course_name,
                COALESCE(ci.course_description, course.course_description) as course_description,
                course.amount,
                course.course_type,
                course.expire_day,
                course.min_reserve_count,
                DATE_FORMAT(course.decide_date, '%Y/%m/%d %H:%i') as decide_date_show
             FROM course
             LEFT JOIN course_info ci ON ci.course_id = course.course_id and ci.lang_type = _lang_type
            WHERE course.course_id = _course_id;
        END
        ";

        \DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sp_get_course_detail_by_campaigncode');
    }
}
