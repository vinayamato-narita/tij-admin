<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentListViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
          CREATE VIEW student_list AS
          (
            select `s`.`student_id` AS `student_id`,`s`.`student_name` AS `student_name`,`s`.`student_email` AS `student_email`,`s`.`student_nickname` AS `student_nickname`,`s`.`student_skypename` AS `student_skypename`,`s`.`last_login_date` AS `last_login_date`,`s`.`direct_mail_flag` AS `direct_mail_flag`,`s`.`student_comment_text` AS `student_comment_text`,count(distinct `lh`.`lesson_history_id`) AS `lesson_count`,`s`.`create_date` AS `create_date`,max(`lh`.`reserve_date`) AS `last_reserve_date`,`s`.`is_tmp_entry` AS `is_tmp_entry`,if(`s`.`course_id` <> 1,'有料','無料') AS `course_name`,coalesce(min(if(`t1`.`course_id` = 1 and `s`.`is_lms_user` = 0,NULL,`t1`.`payment_date`)),'---') AS `first_payment_date`,min(case when `lh`.`student_lesson_reserve_type` = 3 then `ls`.`lesson_starttime` end) AS `first_lesson_date`,if(`s`.`is_lms_user` = 0,`s`.`company_name`,'') AS `company_name`,concat('/',group_concat(distinct nullif(if(`s`.`is_lms_user` = 1,`lc`.`legal_code`,`t1`.`corporation_code`),'') separator '/'),'/') AS `company_code`,`lp`.`project_code` AS `project_code`,`lc`.`company_name` AS `project_company_name`,concat('/',group_concat(distinct `lc`.`company_name` separator '/'),'/') AS `all_project_company_name`,concat('/',group_concat(distinct `lp`.`project_code` separator '/'),'/') AS `all_project_code` from (((((((`student` `s`) left join `point_subscription_history` `t1` on(`t1`.`student_id` = `s`.`student_id` and `t1`.`del_flag` <> 1)) left join `lesson_history` `lh` on(`lh`.`student_id` = `s`.`student_id` and `lh`.`student_lesson_reserve_type` in (1,3))) left join `lesson_schedule` `ls` on(`lh`.`lesson_schedule_id` = `ls`.`lesson_schedule_id`)) left join `lms_project_student` `lpcs` on(`lpcs`.`student_id` = `s`.`student_id`)) left join `lms_project` `lp` on(`lp`.`project_id` = `lpcs`.`project_id`)) left join `lms_company` `lc` on(`lp`.`company_id` = `lc`.`company_id`)) group by `s`.`student_id`
          )
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement( 'DROP VIEW student_list' );
    }
}
