<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSpAdminInsertPaymentHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_admin_insert_payment_history`;
        CREATE PROCEDURE `sp_admin_insert_payment_history`(IN _order_id VARCHAR(50),
        IN _student_id INT,
        IN _course_id INT,
        IN _set_course_id INT,
        IN _project_course_id INT,
        IN _parent_id INT,
        IN _payment_type INT,
        IN _payment_date VARCHAR(255),
        IN _start_date VARCHAR(255),
        IN _begin_date VARCHAR(255),
        IN _mc_gross VARCHAR(255),
        IN _management_number VARCHAR(100),
        IN _course_begin_month VARCHAR(100))
BEGIN
    DECLARE _default_customer_code VARCHAR(255) DEFAULT NULL;
    DECLARE _default_corporation_code VARCHAR(255) DEFAULT NULL;
    DECLARE _expire_date VARCHAR(255) DEFAULT '';
    DECLARE _corporation_flag TINYINT DEFAULT NULL;
    
    SELECT lc.favourite_code,lc.legal_code INTO _default_customer_code, _default_corporation_code FROM lms_company lc LEFT JOIN lms_project_course lp ON lc.company_id = lp.company_id WHERE lp.project_course_id = _project_course_id;
    
    IF _project_course_id > 0 THEN
       SET @project_id = (SELECT project_id FROM lms_project_course WHERE project_course_id = _project_course_id);
       SELECT corporation_flag INTO _corporation_flag FROM lms_project lp WHERE lp.project_id = @project_id;
       IF _corporation_flag = 0 THEN
             SET _default_customer_code = NULL;
    END IF;
     END IF;
    
    SET @payment_way = IF(_payment_type >=2, 2, _payment_type);
    SET @paid_status = IF(_payment_type >=2, _payment_type -2, 1);
    
    SET @point_expire_day = (SELECT expire_day FROM course WHERE course_id=_course_id);
    IF _project_course_id > 0 THEn -- LMS user
    SET @score_grace_period = (SELECT COALESCE(score_grace_period, 0) FROM lms_project_course WHERE project_course_id = _project_course_id);   
    SET _expire_date =  DATE_FORMAT(CONCAT(_course_begin_month, '01'), '%Y-%m-%d %H:%i:%s') + INTERVAL (CEIL(@point_expire_day/ 30) + @score_grace_period) MONTH - INTERVAL 1 SECOND;
    ELSE
        SET _expire_date = DATE_ADD(IF(_start_date = '',  NOW(), DATE_FORMAT(_start_date,'%Y-%m-%d 23:59:59')), INTERVAL @point_expire_day  DAY);
    END IF;
    CALL sp_admin_insert_point_subscription(_student_id
                    ,_course_id
                    ,_set_course_id
                    ,_mc_gross
                    ,ROUND(_mc_gross * 0.1)
                    ,'JPY'
                    ,@payment_way
                    ,_payment_date
                    ,_start_date
                    ,_begin_date
                    ,_expire_date
                    ,@paid_status
                    ,''
                    ,'' 
                    ,_order_id
                    ,coalesce(_default_customer_code,'')
                    ,COALESCE(_default_corporation_code,'')
                    ,_management_number
                );
                
    IF _project_course_id > 0 THEN -- LMS user
    SET @project_id = (SELECT project_id FROm lms_project_course WHERE project_course_id = _project_course_id);
       CALL lms_insert_project_course_student(_project_course_id, @project_id, _course_id, _student_id, _start_date, _expire_date, DATE_FORMAT(CONCAT(_course_begin_month, '01'), '%Y-%m-%d'));
    END IF;
    
 -- insert order
    SET @campaign_code = (SELECT campaign_code FROM course WHERE course_id = IF(_set_course_id = 0, _course_id, _set_course_id) );
    SET @orderExist = (SELECT COUNT(order_id) FROM `order` where order_id = _order_id);
    if @orderExist = 0 THEN
        CALL sp_admin_insert_order(
        _order_id,
        _student_id,
        NULL,
        IF(_set_course_id = 0, _course_id, _set_course_id),
        '00000100',
        @campaign_code,
        1,
        'ADMIN ADD',
        NULL ,
        3,
        NULL,
        _payment_date
        );
    END IF
    ;
END
        ";

        \DB::unprepared($procedure);;
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
