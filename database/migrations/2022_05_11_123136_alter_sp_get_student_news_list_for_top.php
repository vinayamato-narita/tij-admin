<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSpGetStudentNewsListForTop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "
        DROP PROCEDURE IF EXISTS `sp_get_student_news_list_for_top`;
        CREATE PROCEDURE `sp_get_student_news_list_for_top`(
            IN _limit INT,
            IN _lang_type varchar(10),
			IN _student_id INT

            )
BEGIN
            SET @timezone_id = COALESCE((SELECT timezone_id FROM student WHERE student_id = _student_id),1);
            SET @time_diff_from = COALESCE((SELECT diff_time * 60 FROM timezone tz WHERE tz.timezone_id = 1 LIMIT 1),9 * 60);
            SET @time_diff_to = COALESCE((SELECT diff_time * 60 FROM timezone WHERE timezone_id = @timezone_id),9 * 60);
              SELECT
                an.news_id
                ,Replace(Replace(coalesce(ani.news_title,an.news_title), Char(13), ''),Char(10), '')  AS news_title
                ,Replace(Replace(COALESCE(ani.news_body,an.news_body), Char(13), ''),Char(10), '')   AS news_body
                ,news_subject_id
                ,news_update_date
								,DATE_FORMAT(DATE_ADD(DATE_ADD(news_update_date,INTERVAL -@time_diff_from MINUTE),INTERVAL @time_diff_to MINUTE),'%Y/%m/%d %H:%i:%s')   AS news_update_date_timezone
              FROM
                admin_news an  
                LEFT JOIN admin_news_info ani ON an.news_id = ani.news_id and ani.lang_type = _lang_type
              WHERE
                news_subject_id IN (1,3)
                AND  an.is_show_on_student_top = 1
                AND an.news_update_date >= DATE_SUB(NOW(), INTERVAL 365 DAY)
              ORDER BY
                news_update_date DESC
              LIMIT _limit;
            END
        ";

        \DB::unprepared($procedure1);
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
