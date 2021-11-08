<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureAdminCsvExport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "DROP PROCEDURE IF EXISTS `lms_sp_get_lesson_history_for_export_csv_supergrace`;
        CREATE PROCEDURE `lms_sp_get_lesson_history_for_export_csv_supergrace`(IN _expire_date VARCHAR(20),
    IN _corporation_code VARCHAR(255) ,
    IN _course_code VARCHAR(255),
    IN _customer_code VARCHAR(255),
    IN _campaign_code VARCHAR(255),
    IN _company_name INT,
    IN _company_name_text VARCHAR(255),
    IN _project_code VARCHAR(255),
    IN _type INT)
BEGIN
SELECT
        DISTINCT
        pm.point_subscription_history_id,
        DATE_FORMAT(ls.lesson_date,'%Y%m%d') AS lesson_date,      
        IF(skype_voice_rating_from_teacher =0, 1, 0) AS study_result,
        lh.marks,
        l.is_test_lesson,
        co.campaign_code,
        lh.lesson_history_id
    FROM
         lesson_history lh
        LEFT JOIN student st ON lh.student_id = st.student_id
        LEFT JOIN lesson_schedule ls ON lh.lesson_schedule_id = ls.lesson_schedule_id
        LEFT JOIN lesson l ON l.lesson_id = ls.lesson_id
        LEFT JOIN teacher tchr ON ls.teacher_id = tchr.teacher_id
        LEFT JOIN student_point_history sph ON ls.lesson_schedule_id = sph.lesson_schedule_id AND lh.student_id = sph.student_id
        LEFT JOIN point_subscription_history pm 
        ON lh.course_id = pm.course_id AND lh.student_id = pm.student_id AND pm.point_subscription_history_id = sph.point_subscription_id
    LEFT JOIN `order` co ON pm.order_id = co.order_id
    INNER JOIN lms_project_course_student lpcs ON lpcs.point_subscription_id = pm.point_subscription_history_id
    LEFT JOIN lms_project lpMain ON lpMain.project_id = lpcs.project_id
    LEFT JOIN lms_company lcMain ON lpMain.company_id = lcMain.company_id
    LEFT JOIN (SELECT *
            FROM (SELECT PA.project_id, A.`default_project_code`
                FROM lms_project_admin PA
                JOIN lms_admin A ON A.`admin_id` = PA.`admin_id`
                WHERE PA.delete_flag = 0 AND PA.tsv_delete_flag = 0 AND A.`delete_flag` = 0 AND A.`default_project_code` IS NOT NULL
                ORDER BY PA.project_id, PA.default_flag DESC, A.default_project_code ASC
                ) t  
            GROUP BY project_id
        ) Datacode ON Datacode.project_id = lpcs.project_id
    LEFT JOIN lms_project lp ON lp.`project_code` = Datacode.`default_project_code`
    LEFT JOIN lms_company lc ON lp.company_id = lc.company_id
    WHERE
    st.is_lms_user = 1
    AND CASE _type WHEN 0 THEN lcMain.exported_object IN (1,3)
            WHEN 1 THEN lcMain.exported_object = 2
            ELSE 1 = 2
            END
        AND COALESCE(pm.course_code,'') LIKE CONCAT('%',_course_code,'%')
        AND COALESCE(pm.customer_code,'') LIKE CONCAT('%',_customer_code,'%')
        AND COALESCE(co.corporation_code, pm.corporation_code,'') LIKE CONCAT('%',_corporation_code,'%')
    AND COALESCE(co.campaign_code,'') LIKE CONCAT('%',_campaign_code,'%')
    AND COALESCE(lc.company_name,'') LIKE CONCAT('%',_company_name_text,'%')
        AND sph.expire_date >= _expire_date
        AND CASE
        WHEN _company_name = 1 THEN st.company_name IS NOT NULL AND st.company_name <>''
        ELSE 1 = 1
        END
    AND CASE 
        WHEN _project_code = '' THEN 1=1
        ELSE lp.project_code = _project_code
        END
        AND lh.student_lesson_reserve_type= 3 AND ls.is_lesson_end = 1
    ORDER BY
        pm.point_subscription_history_id ASC, lesson_date ASC,lesson_starttime ASC
    ;
END";
  
        \DB::unprepared($procedure1);

        $procedure2 = "DROP PROCEDURE IF EXISTS `lms_sp_get_payment_for_export_csv_supergrace`;
        CREATE PROCEDURE `lms_sp_get_payment_for_export_csv_supergrace`(IN _expire_date VARCHAR(20),
IN _corporation_code VARCHAR(255),
IN _product_code VARCHAR(255),
IN _customer_code VARCHAR(255),
IN _campaign_code VARCHAR(255),
IN _company_name INT,
IN _company_name_text VARCHAR(255),
IN _project_code VARCHAR(255),
IN _type INT)
BEGIN
     SELECT
        psh.point_subscription_history_id
        ,s.student_name
        ,s.student_first_name_kata
        ,s.student_last_name_kata
        ,lps.employee_number
        ,lps.department_number
        ,psh.management_number
        ,DATE_FORMAT(psh.payment_date, '%Y%m%d') AS payment_date
        ,DATE_FORMAT(psh.point_expire_date, '%Y%m%d') AS point_expire_date
        ,psh.point_count
        ,lpc.price ,
        c.course_name_short AS item_name,
        psh.course_id,
        eo.campaign_code,
        lp.project_code,
        lpc.complete_require,
        lpc.complete_require_type,
        lpc.complete_require_marks,
        lpcs.course_code
        ,DATE_FORMAT(psh.begin_date, '%Y%m%d') AS begin_date
    FROM
        point_subscription_history psh
        LEFT JOIN student s ON s.student_id = psh.student_id
        LEFT JOIN  `order` eo ON  eo.order_id = psh.order_id
        INNER JOIN course c ON c.course_id = psh.course_id
        INNER JOIN lms_project_course_student lpcs ON lpcs.point_subscription_id = psh.point_subscription_history_id
        INNER JOIN lms_project_student lps ON lps.project_id = lpcs.project_id AND lps.student_id = s.student_id
    LEFT JOIN lms_project lpMain ON lpMain.project_id = lpcs.project_id
    LEFT JOIN lms_company lcMain ON lpMain.company_id = lcMain.company_id
    LEFT JOIN lms_project_course lpc ON lpcs.project_course_id = lpc.project_course_id
    LEFT JOIN (SELECT *
            FROM (SELECT PA.project_id, A.`default_project_code`
                FROM lms_project_admin PA
                JOIN lms_admin A ON A.`admin_id` = PA.`admin_id`
                WHERE PA.delete_flag = 0 AND PA.tsv_delete_flag = 0 AND A.`delete_flag` = 0 AND A.`default_project_code` IS NOT NULL
                ORDER BY PA.project_id, PA.default_flag DESC, A.default_project_code ASC
                ) t  
            GROUP BY project_id
        ) Datacode ON Datacode.project_id = lpcs.project_id
    LEFT JOIN lms_project lp ON lp.`project_code` = Datacode.`default_project_code`
    LEFT JOIN lms_company lc ON lp.company_id = lc.company_id
    WHERE
        s.is_lms_user = 1
        AND CASE _type WHEN 0 THEN lcMain.exported_object IN (1,3)
            WHEN 1 THEN lcMain.exported_object = 2
            ELSE 1 = 2
            END
        AND psh.point_expire_date  >= _expire_date
        AND COALESCE(psh.course_code, '') LIKE CONCAT('%',_product_code,'%')
        AND COALESCE(psh.customer_code, '') LIKE CONCAT('%',_customer_code,'%')
        AND COALESCE(psh.corporation_code, '') LIKE CONCAT('%',_corporation_code,'%')
        AND COALESCE(eo.campaign_code,'') LIKE CONCAT('%',_campaign_code,'%')
        AND COALESCE(lc.company_name,'') LIKE CONCAT('%',_company_name_text,'%')
        AND CASE
        WHEN _company_name = 1 THEN s.company_name IS NOT NULL AND s.company_name <>''
        ELSE 1 = 1
        END
    AND CASE 
        WHEN _project_code = '' THEN 1=1
        ELSE lp.project_code = _project_code
        END
        AND del_flag != 1
    AND s.student_id IS NOT NULL
    ORDER BY lp.project_code ASC, psh.point_subscription_history_id ASC
    ;
END";
  
        \DB::unprepared($procedure2);

        $procedure3 = "DROP PROCEDURE IF EXISTS `lms_sp_get_student_bought_course`;
        CREATE PROCEDURE `lms_sp_get_student_bought_course`(IN _date_from VARCHAR(10),
    IN _date_to VARCHAR(10),
    IN _payment_date_from VARCHAR(20),
    IN _payment_date_to VARCHAR(20),
    IN _student_name VARCHAR(255))
BEGIN
    SELECT 
    lpcs.point_subscription_id,
    s.student_id,
        s.student_name,
        s.student_first_name,
        s.student_last_name,
        s.student_first_name_kata,
        s.student_last_name_kata,
        s.student_name_kana,
        s.student_email,
        student_birthday,
        s.student_sex,
        s.is_sending_dm,
        s.company_name,
        s.student_home_tel,
        s.student_company_tel,
        s.postcode,
        s.prefecture_number,
        CONCAT(lprec.prefecture_name, s.student_address) student_address,
        s.student_address1,
        s.student_address2,
        s.student_address3,
        lps.department_number,
        lps.employee_number,
        lpc.education_corporation_order_number,
        lp.project_code,
        lpcs.other_department_management_number,
        lc.bill_address,
        psh.management_number as common_mgt_no,
        c.paypal_item_number,
        lpc.price,
                psh.payment_date 
    FROM lms_project_course_student lpcs 
    LEFT JOIN student s ON s.student_id =lpcs.student_id    
    LEFT JOIN lms_project_course lpc ON lpc.project_course_id = lpcs.project_course_id
    LEFT JOIN lms_project lp ON lp.project_id =lpcs.project_id
    LEFT JOIN lms_project_student lps ON lps.project_id = lp.project_id and lps.student_id = s.student_id
    LEFT JOIN course c ON c.course_id =lpcs.course_id
    LEFT JOIN lms_company lc ON lc.company_id =lp.company_id
    LEFT JOIN lms_prefecture lprec ON s.prefecture_number = lprec.prefecture_id
    LEFT JOIN point_subscription_history psh ON psh.point_subscription_history_id = lpcs.point_subscription_id
    WHERE
        lpcs.delete_flag = 0 
        AND lpcs.course_begin_month BETWEEN _date_from AND _date_to
        AND lpc.delete_flag = 0
        AND lp.delete_flag = 0
        AND psh.del_flag = 0
        AND COALESCE(s.student_name,'') LIKE CONCAT('%',_student_name,'%')
        AND psh.payment_date BETWEEN _payment_date_from AND _payment_date_to
    ;
    END";
  
        \DB::unprepared($procedure3);

        $procedure4 = "DROP PROCEDURE IF EXISTS `sp_get_lesson_history_for_export_csv`;
        CREATE PROCEDURE `sp_get_lesson_history_for_export_csv`(IN _dateFrom VARCHAR(10),
    IN _dateTo VARCHAR(10),
    IN _payment_id VARCHAR(10),
    IN _corporation_code VARCHAR(255) ,
    IN _course_code VARCHAR(255),
    IN _customer_code VARCHAR(255))
BEGIN
SELECT
        DISTINCT
        ls.teacher_id,
        tchr.teacher_name,
        tchr.payment_des_code,
        
        (CASE WHEN sph.point_subscription_id = 0 THEN  pm.point_subscription_history_id ELSE sph.point_subscription_id END) AS  orders_id ,
        stdn.student_id,
        pm.course_code AS product_code,
        DATE_FORMAT(sph.start_date,'%Y%m%d') AS start_date,
        (PERIOD_DIFF(DATE_FORMAT(ls.lesson_date,'%Y%m'),DATE_FORMAT(pm.payment_date,'%Y%m'))+1) AS months,
        ls.lesson_date,
        CONCAT(DATE_FORMAT(ls.lesson_starttime,'%H:%i'),'~',DATE_FORMAT(ls.lesson_endtime,'%H:%i')) AS lesson_time,
        pm.customer_code,
        pm.corporation_code,
        IF(stdn.is_lms_user = 1, lpc.company_name,stdn.company_name) AS company_name,
        REPLACE(REPLACE(pm.item_name,'\r\n',' '),'\r',' ')  AS item_name,
        lsn.lesson_name,
        COALESCE(ls.lesson_text_name, lst.lesson_text_name) AS lesson_text_name,
        teacher_rating,
        teacher_attitude,
        teacher_punctual,
        skype_voice_rating_from_student,
        skype_voice_rating_from_teacher,
        REPLACE(REPLACE(REPLACE(comment_from_student_to_teacher,'\r\n',' '),'\r',' '),'\n',' ') AS comment_from_student_to_teacher,
        REPLACE(REPLACE(REPLACE(comment_from_student_to_office,'\r\n',' '),'\r',' '),'\n',' ')  AS comment_from_student_to_office,
        REPLACE(REPLACE(REPLACE(comment_from_teacher_to_office,'\r\n',' '),'\r',' '),'\n',' ')  AS comment_from_teacher_to_office,
        stdn.student_name,
        co.campaign_code,
        REPLACE(REPLACE(REPLACE(comment_from_teacher_to_student,'\r\n',' '),'\r',' '),'\n',' ')  AS comment_from_teacher_to_student
    FROM
         lesson_history lh
        LEFT JOIN lesson_schedule ls ON lh.lesson_schedule_id = ls.lesson_schedule_id
        LEFT JOIN teacher tchr ON ls.teacher_id = tchr.teacher_id
        LEFT JOIN student stdn ON lh.student_id = stdn.student_id
        LEFT JOIN lesson lsn ON ls.lesson_id = lsn.lesson_id
        LEFT JOIN lesson_text lst ON ls.lesson_text_id = lst.lesson_text_id
        LEFT JOIN student_point_history sph ON ls.lesson_schedule_id = sph.lesson_schedule_id AND lh.student_id = sph.student_id
        LEFT JOIN point_subscription_history pm ON sph.point_subscription_id = pm.point_subscription_history_id
        LEFT JOIN `order` co ON pm.order_id = co.order_id
    LEFT JOIN lms_project_course_student lpcs ON pm.point_subscription_history_id = lpcs.point_subscription_id
    LEFT JOIN lms_project lp ON lp.project_id = lpcs.project_id
    LEFT JOIN lms_company lpc ON lp.company_id = lpc.company_id
    WHERE
        CASE WHEN _payment_id > 0 THEN pm.point_subscription_history_id  = _payment_id
             ELSE 1=1 END
        AND COALESCE(pm.course_code,'') LIKE CONCAT('%',_course_code,'%')
        AND COALESCE(pm.customer_code,'') LIKE CONCAT('%',_customer_code,'%')
        AND COALESCE(co.corporation_code, pm.corporation_code,'') LIKE CONCAT('%',_corporation_code,'%')
        
        AND ls.lesson_date BETWEEN DATE_FORMAT(_dateFrom,'%Y-%m-%d 00:00:00') AND DATE_FORMAT(_dateTo,'%Y-%m-%d 23:59:59')
        AND lh.student_lesson_reserve_type= 3 AND ls.is_lesson_end = 1
    ORDER BY
        lesson_date ASC,lesson_starttime ASC
    ;
END";
  
        \DB::unprepared($procedure4);

        $procedure5 = "DROP PROCEDURE IF EXISTS `sp_get_lesson_history_for_export_csv_supergrace`;
        CREATE PROCEDURE `sp_get_lesson_history_for_export_csv_supergrace`(IN _student_id VARCHAR (2000),
     IN _expire_date VARCHAR(20),
    IN _corporation_code VARCHAR(255) ,
    IN _course_code VARCHAR(255),
    IN _customer_code VARCHAR(255),
    IN _campaign_code VARCHAR(255),
    IN _company_name INT,
    IN _company_name_text VARCHAR(255))
BEGIN
SELECT
        DISTINCT
        pm.point_subscription_history_id,
        DATE_FORMAT(ls.lesson_date,'%Y%m%d') AS lesson_date,      
        IF(skype_voice_rating_from_teacher =0, 1, 0) AS study_result,
        co.campaign_code,
        lh.lesson_history_id
    FROM
         lesson_history lh
        LEFT JOIN student st ON lh.student_id = st.student_id
        LEFT JOIN lesson_schedule ls ON lh.lesson_schedule_id = ls.lesson_schedule_id
        LEFT JOIN teacher tchr ON ls.teacher_id = tchr.teacher_id
        LEFT JOIN student_point_history sph ON ls.lesson_schedule_id = sph.lesson_schedule_id AND lh.student_id = sph.student_id
        LEFT JOIN point_subscription_history pm 
            ON lh.course_id = pm.course_id AND lh.student_id = pm.student_id AND pm.point_subscription_history_id = sph.point_subscription_id
            AND DATE_FORMAT(pm.point_expire_date,'%Y-%m-%d') = DATE_FORMAT(sph.expire_date,'%Y-%m-%d')
    LEFT JOIN `order` co ON pm.order_id = co.order_id
     WHERE
    st.is_lms_user = 0
    -- AND sph.point_count <=20
        AND COALESCE(pm.course_code,'') LIKE CONCAT('%',_course_code,'%')
        AND COALESCE(pm.customer_code,'') LIKE CONCAT('%',_customer_code,'%')
        AND COALESCE(co.corporation_code, pm.corporation_code,'') LIKE CONCAT('%',_corporation_code,'%')
    AND COALESCE(co.campaign_code,'') LIKE CONCAT('%',_campaign_code,'%')
    AND COALESCE(st.company_name,'') LIKE CONCAT('%',_company_name_text,'%')
        AND sph.expire_date >= _expire_date
        AND CASE
        WHEN _company_name = 1 THEN st.company_name IS NOT NULL AND st.company_name <>''
        ELSE 1 = 1
        END
    
        AND lh.student_lesson_reserve_type= 3 AND ls.is_lesson_end = 1
        AND CASE WHEN _student_id <> '' THEN FIND_IN_SET(st.student_id, _student_id)  > 0 ELSE 1 = 1 END
        -- AND st.student_id IN (1,2,3)
    ORDER BY
        pm.point_subscription_history_id ASC, lesson_date ASC,lesson_starttime ASC
    ;
END";
  
        \DB::unprepared($procedure5);

        $procedure6 = "DROP PROCEDURE IF EXISTS `sp_get_lesson_summary_for_export_csv`;
        CREATE PROCEDURE `sp_get_lesson_summary_for_export_csv`(IN _dateFrom VARCHAR(255),
    IN _dateTo VARCHAR(255),
    IN _expireDateFrom VARCHAR(255),
    IN _expireDateTo VARCHAR(255),
    IN _corporation_code VARCHAR(255),
    IN _project_code VARCHAR(255))
BEGIN
    SELECT 
    te.teacher_id,
    te.teacher_name,
    s.`student_name`,
    s.student_id,
    lp.`project_code`,
    IF(s.is_lms_user = 1, lc.company_name,s.company_name) AS company_name,
    coalesce(lc.legal_code,psh.corporation_code) AS corporation_code,
    COALESCE(lc.favourite_code,psh.customer_code) AS customer_code,
    psh.`point_subscription_history_id`,
    psh.course_code,
    o.campaign_code,
    COALESCE(sc.course_name, '') as set_course_name,
    REPLACE(REPLACE(c.course_name,'\r\n',' '),'\r',' ') AS course_name,
    DATE_FORMAT(sph.start_date,'%Y%m%d') AS start_date,
    DATE_FORMAT(coalesce(psh.begin_date, sph.start_date),'%Y%m%d') AS begin_date,
    DATE_FORMAT(sph.`expire_date`,'%Y%m%d') AS expire_date,
    (PERIOD_DIFF(DATE_FORMAT(ls.lesson_date,'%Y%m'),DATE_FORMAT(COALESCE(psh.payment_date, sph.start_date),'%Y%m'))+1) AS months,
    DATE_FORMAT(ls.`lesson_date`,'%Y%m%d') AS lesson_date,
    CONCAT(DATE_FORMAT(ls.lesson_starttime,'%H:%i'),'~',DATE_FORMAT(ls.lesson_endtime,'%H:%i')) AS lesson_time,
    lsn.lesson_name,
    lst.lesson_text_name,
    teacher_rating,
    teacher_attitude,
    teacher_punctual,
    skype_voice_rating_from_student,
    skype_voice_rating_from_teacher,
    REPLACE(REPLACE(REPLACE(comment_from_student_to_teacher,'\r\n',' '),'\r',' '),'\n',' ') AS comment_from_student_to_teacher,
    REPLACE(REPLACE(REPLACE(`comment_from_student_to_office`,'\r\n',' '),'\r',' '),'\n',' ') AS comment_from_student_to_office,
    REPLACE(REPLACE(REPLACE(`comment_from_teacher_to_student`,'\r\n',' '),'\r',' '),'\n',' ')  AS comment_from_teacher_to_student,
    REPLACE(REPLACE(REPLACE(comment_from_teacher_to_office,'\r\n',' '),'\r',' '),'\n',' ')  AS comment_from_teacher_to_office,
    COALESCE(lpc.`complete_require`, 80) AS complete_require,
    COALESCE(psh.point_count,c.point_count,1) AS point_count,
    lh.course_id,
    IF (sph.`point_subscription_id` IS NULL OR sph.`point_subscription_id` = 0, 0, (
      SELECT COUNT(*) FROM lesson_history lh1 
        LEFT JOIN lesson_schedule ls1 ON ls1.lesson_schedule_id = lh1.lesson_schedule_id
        LEFT JOIN student_point_history sph1 ON ls1.lesson_schedule_id = sph1.lesson_schedule_id
        WHERE sph1.point_subscription_id = sph.`point_subscription_id`
        AND lh1.skype_voice_rating_from_teacher = 0 
        AND lh1.student_lesson_reserve_type= 3
    )) AS attend
    FROM lesson_history lh
        JOIN lesson_schedule ls ON ls.lesson_schedule_id = lh.lesson_schedule_id
        LEFT JOIN teacher te ON te.teacher_id = ls.teacher_id
        LEFT JOIN student s ON lh.student_id = s.student_id
        LEFT JOIN course c ON c.course_id = lh.course_id
        LEFT JOIN lesson lsn ON ls.lesson_id = lsn.lesson_id
        LEFT JOIN lesson_text lst ON ls.lesson_text_id = lst.lesson_text_id
        LEFT JOIN student_point_history sph ON ls.lesson_schedule_id = sph.lesson_schedule_id
        LEFT JOIN point_subscription_history psh on psh.`point_subscription_history_id` = sph.`point_subscription_id`
        LEFT JOIN course sc ON sc.course_id = psh.set_course_id 
        LEFT JOIN `order` o ON psh.order_id = o.order_id
        LEFT JOIN lms_project_course_student lpcs ON lpcs.point_subscription_id = psh.point_subscription_history_id
        LEFT JOIN lms_project_course lpc ON lpc.project_course_id = lpcs.project_course_id
        LEFT JOIN lms_project lp ON lp.project_id = lpcs.project_id
        LEFT JOIN lms_company lc ON lp.company_id = lc.company_id
    WHERE 
     CASE WHEN _project_code <> '' THEN lp.`project_code`  = _project_code
     ELSE 1=1 END
     AND COALESCE(lc.legal_code,psh.corporation_code,'') LIKE CONCAT('%',_corporation_code,'%')
     AND CASE WHEN _dateFrom <> '' THEN ls.lesson_date >= DATE_FORMAT(_dateFrom,'%Y-%m-%d 00:00:00') ELSE 1 = 1 END
     AND CASE WHEN _dateTo <> '' THEN ls.lesson_date <= DATE_FORMAT(_dateTo,'%Y-%m-%d 00:00:00') ELSE 1 = 1 END
     AND CASE WHEN _expireDateFrom <> '' THEN sph.expire_date >= DATE_FORMAT(_expireDateFrom,'%Y-%m-%d 00:00:00') ELSE 1 = 1 END
     AND CASE WHEN _expireDateTo <> '' THEN sph.expire_date <= DATE_FORMAT(_expireDateTo,'%Y-%m-%d 23:59:59') ELSE 1 = 1 END
     AND lh.student_lesson_reserve_type= 3
     ORDER BY
     lesson_date ASC,lesson_starttime ASC
    ;
END";
  
        \DB::unprepared($procedure6);

        $procedure7 = "DROP PROCEDURE IF EXISTS `sp_get_payment_for_export_csv`;
        CREATE PROCEDURE `sp_get_payment_for_export_csv`(IN _payment_date_1 VARCHAR(12),
    IN _payment_date_2 VARCHAR(12),
    IN _corporation_code VARCHAR(255),
    IN _product_code VARCHAR(255),
    IN _customer_code VARCHAR(255))
BEGIN
     SELECT
        psh.point_subscription_history_id
        ,psh.student_id
        ,psh.corporation_code
        ,IF(s.is_lms_user = 1, lpc.company_name,s.company_name) AS company_name
        ,psh.customer_code
        ,psh.course_code AS product_code
        ,eo.campaign_code
        ,psh.payment_date AS received_orderes_date
        ,(SELECT start_date FROM student_point_history sph WHERE sph.point_subscription_id = psh.point_subscription_history_id ORDER BY sph.student_point_history_id LIMIT 1) AS start_date
        ,psh.point_expire_date
        ,psh.point_count
        ,psh.amount
        ,CASE  psh.payment_way
                WHEN 0 THEN '[G]'
                WHEN 1 THEN  '[CSV]'
                WHEN 2 THEN '[GCO]'
                ELSE ''
         END  pay_way -- (SELECT payment_type_name FROM payment_type WHERE payment_type = IF(psh.payment_way = 2, psh.payment_way + psh.paid_status, psh.payment_way)) AS pay_way
         ,c.course_id
    FROM
        point_subscription_history psh
        LEFT JOIN student s ON s.student_id = psh.student_id
        LEFT JOIN  `order` eo ON  eo.order_id = psh.order_id
        INNER JOIN course c ON c.course_id = psh.course_id
        LEFT JOIN lms_project_course_student lpcs ON psh.point_subscription_history_id = lpcs.point_subscription_id
        LEFT JOIN lms_project lp ON lp.project_id = lpcs.project_id
        LEFT JOIN lms_company lpc ON lp.company_id = lpc.company_id
    WHERE
        psh.payment_date BETWEEN DATE_FORMAT(_payment_date_1,'%Y-%m-%d 00:00:00') AND DATE_FORMAT(_payment_date_2,'%Y-%m-%d 23:59:59')
        AND COALESCE(psh.course_code, '') LIKE CONCAT('%',_product_code,'%')
        AND COALESCE(psh.customer_code, '') LIKE CONCAT('%',_customer_code,'%')
        AND COALESCE(psh.corporation_code, '') LIKE CONCAT('%',_corporation_code,'%')
        AND del_flag != 1
        AND s.student_id IS NOT NULL
    ORDER BY psh.point_subscription_history_id ASC
    ;
END";
  
        \DB::unprepared($procedure7);

        $procedure8 = "DROP PROCEDURE IF EXISTS `sp_get_payment_for_export_csv_supergrace`;
        CREATE PROCEDURE `sp_get_payment_for_export_csv_supergrace`(IN _student_id VARCHAR (2000),
IN _expire_date VARCHAR(20),
IN _corporation_code VARCHAR(255),
IN _product_code VARCHAR(255),
IN _customer_code VARCHAR(255),
IN _campaign_code VARCHAR(255),
IN _company_name INT,
IN _company_name_text VARCHAR(255))
BEGIN
     SELECT
        psh.point_subscription_history_id
        ,s.student_name
        ,s.student_first_name_kata
        ,s.student_last_name_kata
        ,psh.management_number
        ,'' AS employee_number
        ,'' AS department_number
        ,DATE_FORMAT(psh.payment_date, '%Y%m%d') AS payment_date
        ,DATE_FORMAT(psh.point_expire_date, '%Y%m%d') AS point_expire_date
        ,psh.point_count
        ,c.amount AS price,
        c.course_name_short  AS item_name,
        psh.course_id,
        eo.campaign_code,
        '' AS project_code,
        80 AS complete_require
        ,DATE_FORMAT(sph.start_date, '%Y%m%d') AS start_date
    FROM
        point_subscription_history psh
        LEFT JOIN student s ON s.student_id = psh.student_id
        LEFT JOIN  `order` eo ON  eo.order_id = psh.order_id
        INNER JOIN course c ON c.course_id = psh.course_id
        LEFT JOIN student_point_history sph ON sph.student_id = psh.student_id AND psh.point_subscription_history_id = sph.point_subscription_id AND sph.point_count > 0
     WHERE
         s.is_lms_user = 0
         -- AND psh.point_count <=20
        AND psh.point_expire_date  >= _expire_date
        AND COALESCE(psh.course_code, '') LIKE CONCAT('%',_product_code,'%')
        AND COALESCE(psh.customer_code, '') LIKE CONCAT('%',_customer_code,'%')
        AND COALESCE(psh.corporation_code, '') LIKE CONCAT('%',_corporation_code,'%')
        AND COALESCE(eo.campaign_code,'') LIKE CONCAT('%',_campaign_code,'%')
        AND COALESCE(s.company_name,'') LIKE CONCAT('%',_company_name_text,'%')
        AND CASE
        WHEN _company_name = 1 THEN s.company_name IS NOT NULL AND s.company_name <>''
        ELSE 1 = 1
        END
        AND del_flag != 1
        AND CASE WHEN _student_id <> '' THEN FIND_IN_SET(s.student_id, _student_id)  > 0 ELSE 1 = 1 END
    -- AND s.student_id IN (1,2)
    ORDER BY psh.point_subscription_history_id ASC
    ;
END";
  
        \DB::unprepared($procedure8);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_procedure_admin_csv_export');
    }
}
