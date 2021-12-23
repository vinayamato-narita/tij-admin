<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForGetCourseListByCampaigncode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DELIMITER //
        DROP PROCEDURE IF EXISTS `sp_get_course_list_by_campaigncode`;
        CREATE PROCEDURE `sp_get_course_list_by_campaigncode`(IN _campaign_code VARCHAR(50),
        IN _category_id INT,
        IN _course_type INT,
        IN _lang_type VARCHAR(10))
        BEGIN
            SELECT course.course_id,
                COALESCE(ci.course_name, course.course_name) as course_name,
                COALESCE(ci.course_description, course.course_description) as course_description,
                course.amount,
                course.course_type,
                course.expire_day,
                course.min_reserve_count,
                DATE_FORMAT(course.decide_date, '%Y/%m/%d %H:%i') as decide_date_show,
                ca.category_id,
                COALESCE(cai.category_name, ca.category_name) AS category_name,
                (CASE WHEN ca.order_num is null THEN (select max(order_num) + 1 from category) ELSE ca.order_num END) as feildssort
             FROM course
             LEFT JOIN course_info ci ON ci.course_id = course.course_id and ci.lang_type = _lang_type
             LEFT JOIN category_course as cc ON cc.course_id = course.course_id
             LEFT JOIN category as ca ON ca.category_id = cc.category_id
             LEFT JOIN category_info cai ON cai.category_id = ca.category_id AND cai.lang_type = _lang_type
            WHERE CASE WHEN _category_id <> 0 THEN ca.category_id = _category_id ELSE 1 = 1 END
               AND CASE WHEN _course_type <> 999 THEN course.course_type = _course_type ELSE 1 = 1 END
             ORDER BY course.course_type ASC, ca.order_num ASC, course.display_order ASC;
        END //
        DELIMITER ;
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
        Schema::dropIfExists('sp_get_course_list_by_campaigncode');
    }
}
