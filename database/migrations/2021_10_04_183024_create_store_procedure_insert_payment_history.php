<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureInsertPaymentHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "DROP PROCEDURE IF EXISTS `lms_insert_project_course_student`;
CREATE PROCEDURE `lms_insert_project_course_student`(IN _project_course_id INT,
            IN _project_id INT,
            IN _course_id INT,
            IN _student_id INT,
            IN _start_date VARCHAR(20),
            IN _expire_date VARCHAR(20),
            IN _other_department_prior VARCHAR(20))
BEGIN
    DECLARE _tmp VARCHAR(20);
    DECLARE _offset VARCHAR(20) DEFAULT '3';
    DECLARE _first INT;
    DECLARE _course_code VARCHAR(15) DEFAULT NULL;
    DECLARE _parent_course_code VARCHAR(15) DEFAULT NULL;
    
    SET _parent_course_code = (
    SELECT COALESCE(parent.course_code, '') 
     FROM lms_project_course lpc
     LEFT JOIN lms_project_course parent ON lpc.parent_id = parent.project_course_id
     WHERE lpc.project_id = _project_id
     AND lpc.project_course_id = _project_course_id
     LIMIT 1
    );
    
    SET _course_code = (
    SELECT COALESCE(lpc.course_code, '') 
     FROM lms_project_course lpc
     WHERE lpc.project_id = _project_id
     AND lpc.project_course_id = _project_course_id
     LIMIT 1
    );
    
    
    SET _first = (
    SELECT COUNT(*) FROM lms_project_course_student lpcs
    LEFT JOIN point_subscription_history psh ON lpcs.`point_subscription_id` = psh.`point_subscription_history_id`
    WHERE lpcs.project_id = _project_id
    AND lpcs.student_id = _student_id
    AND psh.course_id > 1
    AND psh.`del_flag` = 0
    );
    SET _tmp = (
    SELECT 
    COALESCE(
    MAX(other_department_management_number), 
    CONVERT(CONCAT(YEAR(_other_department_prior), LPAD(MONTH(_other_department_prior), 2, '0'), _offset, '00000'), UNSIGNED INTEGER))+1 
    FROM lms_project_course_student
    JOIN student ON student.student_id = lms_project_course_student.student_id
    WHERE 
    other_department_management_number LIKE CONCAT(YEAR(_other_department_prior), LPAD(MONTH(_other_department_prior), 2, '0'), '%')
    OR other_department_management_number IS NULL
    );
    SET _tmp = COALESCE(_tmp, CONCAT(YEAR(_other_department_prior), LPAD(MONTH(_other_department_prior), 2, '0'), _offset, '00001'));
    INSERT INTO lms_project_course_student(
    project_course_id,
    project_id,
    course_id,
    student_id,
    start_date,
    expired_date,
    course_begin_month,
    point_subscription_id,
    other_department_management_number,
    created,
    modified,
    delete_flag,
    course_code,
    parent_course_code
    )
    VALUES (
    _project_course_id,
    _project_id,
    _course_id,
    _student_id,
    _start_date,
    _expire_date,
    CONCAT(YEAR(_other_department_prior), LPAD(MONTH(_other_department_prior), 2, '0')),
    (SELECT MAX(point_subscription_history_id) FROM point_subscription_history WHERE student_id = _student_id),
    _tmp,
    NOW(),
    NOW(),
    0,
    _course_code,
    _parent_course_code
    );
    IF _first = 0 AND _course_id > 1 THEN
    UPDATE lms_project_student 
    SET buy_course_flag = (SELECT buy_course_continue FROM lms_project WHERE project_id = _project_id) 
    WHERE project_id = _project_id 
    AND student_id = _student_id;
    END IF;
END";
  
        \DB::unprepared($procedure1);

        $procedure2 = "DROP PROCEDURE IF EXISTS `sp_admin_delete_student_point_subscription`;
CREATE PROCEDURE `sp_admin_delete_student_point_subscription`(IN _point_subscription_history_id INT,
    IN _cancel_history_save_flag INT)
BEGIN
    IF _cancel_history_save_flag = 1 THEN
        INSERT INTO lesson_cancel_history (
            `student_id`,
            `teacher_id`,
            `lesson_date`,
            `lesson_starttime`,
            `reserve_date`,
            `cancel_date`,
            `cancel_student_comment`,
            `cancel_admin_comment`,
            `cancel_teacher_comment`
        ) 
        SELECT 
                    lh.student_id,
                    ls.teacher_id,
                    ls.lesson_date,
                    ls.lesson_starttime,
                    lh.reserve_date,
                    NOW(),
                    '',
                    '',
                    ''
                FROM 
                    lesson_history lh
                    join lesson_schedule ls on lh.lesson_schedule_id = ls.lesson_schedule_id
                    JOIN student_point_history sph ON sph.lesson_schedule_id = ls.lesson_schedule_id
                    
                WHERE
                    sph.point_subscription_id = _point_subscription_history_id;
        END IF;
        
        DELETE FROM lesson_schedule
        WHERE lesson_schedule_id IN 
        (SELECT lesson_schedule_id FROM student_point_history WHERE point_subscription_id = _point_subscription_history_id);
        
        DELETE FROM lesson_history
        WHERE lesson_schedule_id IN
        (
        SELECT lesson_schedule_id FROM student_point_history WHERE point_subscription_id = _point_subscription_history_id
        );
        
        DELETE FROM student_point_history WHERE point_subscription_id = _point_subscription_history_id;
        
        DELETE FROM point_subscription_history WHERE point_subscription_history_id = _point_subscription_history_id;
        
        DELETE FROM lms_project_course_student WHERE point_subscription_id = _point_subscription_history_id;
END";
  
        \DB::unprepared($procedure2);

        $procedure3 = "DROP PROCEDURE IF EXISTS `sp_admin_get_course_list`;
CREATE PROCEDURE `sp_admin_get_course_list`()
BEGIN
    SELECT
        course_id
        ,course_name
        ,paypal_item_number AS item_number
        ,point_count
        ,is_set_course
        ,IF(is_set_course = 1,
          (SELECT SUM(child.amount)
              FROM course_set_course sc JOIN course child ON sc.course_id = child.course_id 
              WHERE sc.set_course_id = c.course_id),
              amount
             ) AS price
        ,IF(is_set_course = 1,
          (SELECT GROUP_CONCAT(sc.course_id)
              FROM course_set_course sc
              WHERE sc.set_course_id = c.course_id),
              ''
             ) AS child_ids
        ,is_show
    FROM
        course c 
    WHERE c.is_show = 1           
    ORDER BY
        c.course_name
    ;
END";
  
        \DB::unprepared($procedure3);

        $procedure4 = "DROP PROCEDURE IF EXISTS `sp_admin_get_course_list_lms`;
CREATE PROCEDURE `sp_admin_get_course_list_lms`(IN _student_id INT(11),
    IN _course_free_id INT)
BEGIN
  SELECT 
    lpc.project_course_id,
    lpc.project_id,
    lpc.course_type,
    lpc.parent_id,
    c.course_id,
    c.course_name,
    c.point_count,
    IF(lpc.`course_type` =1, 
    (SELECT SUM(lpc1.price)
         FROM lms_project_course lpc1 
         WHERE lpc1.set_course_id = c.course_id AND lpc1.parent_id = lpc.project_course_id), 
    lpc.price
    ) AS price,
    c.is_show
  FROM
    lms_project_student lps
      LEFT JOIN lms_project_course lpc ON lps.project_id = lpc.project_id
    LEFT JOIN course c ON lpc.course_id = c.course_id
     LEFT JOIN lms_project lp ON lp.project_id = lpc.project_id
   WHERE 
     lps.student_id = _student_id
     AND lps.`buy_course_flag` = 1 
     AND lp.buy_course_flag = 1
     AND lpc.course_type <> 2
     AND lps.`delete_flag` = 0 
     AND lpc.`delete_flag` = 0
     AND lpc.`show_flag` = 1
     AND c.is_show = 1
     and c.course_id <> _course_free_id
     AND NOW() BETWEEN (CASE WHEN lpc.start_date IS NULL THEN '1900-01-01 00:00:00' ELSE lpc.start_date END) AND (CASE WHEN lpc.expired_date IS NULL THEN '2900-12-12 23:59:59' ELSE lpc.expired_date END)
   ORDER BY lps.`created` DESC, lpc.`order_number`, lpc.`price`
  ;
END";
  
        \DB::unprepared($procedure4);

        $procedure5 = "DROP PROCEDURE IF EXISTS `sp_admin_insert_order`;
CREATE PROCEDURE `sp_admin_insert_order`(IN _order_id VARCHAR(27),
IN _student_id INT,
IN _student_card_id BIGINT,
IN _course_id INT,
IN _product_code VARCHAR(45),
IN _campaign_code VARCHAR(45),
IN _order_status TINYINT(3),
IN _order_ip VARCHAR(16),
IN _gmo_error_code VARCHAR(20),
IN _error_step TINYINT(3),
IN _corporation_code VARCHAR(45),
IN _order_date VARCHAR(255))
BEGIN
INSERT INTO `order`
            (`order_id`,
             `student_id`,
             `student_card_id`,
             `course_id`,
             `product_code`,
             `campaign_code`,
             `order_date`,
             `order_status`,
             `order_ip`,
             `gmo_error_code`,
             `error_step`,
             `corporation_code`)
VALUES (_order_id,
        _student_id,
        _student_card_id,
        _course_id,
        _product_code,
        (SELECT campaign_code FROM course WHERE course_id = _course_id),
        _order_date,
        _order_status,
        _order_ip,
        _gmo_error_code,
        _error_step,
        _corporation_code);
END";
  
        \DB::unprepared($procedure5);

        $procedure6 = "DROP PROCEDURE IF EXISTS `sp_admin_insert_payment_history`;
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
    
    SET @point_expire_day = (SELECT point_expire_day FROM course WHERE course_id=_course_id);
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
END";
  
        \DB::unprepared($procedure6);

        $procedure7 = "DROP PROCEDURE IF EXISTS `sp_admin_insert_point_subscription`;
CREATE PROCEDURE `sp_admin_insert_point_subscription`(IN _student_id INT,
IN _course_id INT,
IN _set_course_id INT,
IN _amount VARCHAR(45),
IN _tax VARCHAR(45),
IN _currency_unit VARCHAR(45),
IN _payment_way  INT,
IN _payment_date   VARCHAR(255),
IN _start_date  VARCHAR(255),
IN _begin_date  VARCHAR(255),
IN _expire_date Varchar(255),
IN _paid_status INT,
IN _first_name     VARCHAR(255),
IN _last_name      VARCHAR(255),
IN _order_id       VARCHAR(255),
IN _customer_code VARCHAR (255),
IN _corporation_code  VARCHAR(255),
IN _management_number VARCHAR(100))
BEGIN
    DECLARE _rtn INT DEFAULT 0;
    DECLARE _add_point INT DEFAULT 0;
    
    INSERT INTO point_subscription_history(
            student_id
            ,course_id
            ,set_course_id
            ,amount
            ,tax
            ,currency_unit
            ,payment_way
            ,payment_date
            ,payment_status
            ,update_date
            ,order_id
            ,item_name
            ,point_count
            ,point_expire_date
            ,course_code
            ,customer_code
            ,corporation_code
            ,begin_date
            ,management_number
            ,paid_status
    )
    VALUES (
         _student_id,
         _course_id,
         IF(_set_course_id = 0, NULL, _set_course_id),
         _amount,
         _tax,
         _currency_unit,
         _payment_way,
         IF(_payment_date = '',  NOW(), DATE_FORMAT(_payment_date,'%Y-%m-%d %H:%i:%s')),
         1,
          NOW(),
         _order_id,
         (SELECT course_name FROM course WHERE course_id = _course_id),
         (SELECT point_count FROM course WHERE course_id = _course_id),
         _expire_date,
         (SELECT paypal_item_number FROM course WHERE course_id = _course_id),
         _customer_code,
         _corporation_code
         ,IF(_begin_date = '', IF(_start_date = '',  NOW(), DATE_FORMAT(_start_date,'%Y-%m-%d %H:%i:%s')), DATE_FORMAT(_begin_date,'%Y-%m-%d %H:%i:%s'))
         ,_management_number
         ,_paid_status
    );
    
    SET   _add_point = COALESCE((SELECT point_count FROM course WHERE course_id = _course_id),0);
    CASE  _add_point
      WHEN 0 THEN
        SET _rtn = 1;
      ELSE
          -- Process with course free
          
         CALL sp_disable_course_free(_student_id);
          
          
          INSERT INTO student_point_history
            (student_id,pay_date,pay_description,pay_type,point_count,expire_date,lesson_schedule_id,course_id,start_date,paid_status,point_subscription_id)
              VALUES
               (
                 _student_id
                 ,NOW()
                
                 ,CONCAT('レッスン付与 (',(SELECT course_name FROM course WHERE course_id = _course_id),')')
                 ,_payment_way
                     ,_add_point
                 ,_expire_date
                 ,0
                     ,_course_id
                     ,IF(_start_date = '',  NOW(), DATE_FORMAT(_start_date,'%Y-%m-%d %H:%i:%s'))
                     ,_paid_status
             ,(SELECT MAX(point_subscription_history_id) FROM point_subscription_history WHERE student_id = _student_id)
               );
        
    END CASE;
    UPDATE student
     SET
       course_id = _course_id
       ,course_update_count = course_update_count + 1
     WHERE
       student_id = _student_id
    ;
  
END";
  
        \DB::unprepared($procedure7);

        $procedure8 = "DROP PROCEDURE IF EXISTS `sp_admin_update_payment_history`;
CREATE PROCEDURE `sp_admin_update_payment_history`(IN _student_id INT,
IN _payment_id INT,
IN _payment_type VARCHAR(255),
IN _payment_date VARCHAR(255),
IN _start_date VARCHAR(255),
IN _begin_date VARCHAR(255),
IN _point_expire_date VARCHAR(255),
IN _mc_gross VARCHAR(255),
IN _tax VARCHAR(255),
IN _management_number VARCHAR(100),
IN _course_begin_month VARCHAR(100))
BEGIN
    DECLARE _order_id VARCHAR(32) DEFAULT NULL;
    SET @old_course_id = (SELECT course_id FROM  point_subscription_history WHERE point_subscription_history_id = _payment_id)  ;
    SET @payment_type = IF(_payment_type >=2, 2, _payment_type);
    SET @paid_status = IF(_payment_type >=2, _payment_type -2, 1);
    SET @course_begin_month = IF(_course_begin_month <> '' , _course_begin_month,(SELECT course_begin_month FROM lms_project_course_student WHERE student_id = _student_id AND point_subscription_id = _payment_id));
        
        UPDATE point_subscription_history
        SET
                 payment_way = @payment_type
                ,amount = _mc_gross
                ,tax = _tax
                ,payment_date = _payment_date
                ,begin_date = _begin_date
                ,paid_status = @paid_status
                ,management_number = _management_number
                ,point_expire_date = _point_expire_date
        WHERE
                point_subscription_history_id = _payment_id
        ;

        UPDATE `student_point_history`
        SET 
            start_date = _start_date,
            -- pay_date = _payment_date,
            paid_status = @paid_status,
            expire_date = _point_expire_date
        WHERE 
            student_id = _student_id AND point_subscription_id = _payment_id;
        -- update project_course_student
        UPDATE lms_project_course_student
        SET 
        start_date = _start_date,
        expired_date = _point_expire_date,
        course_begin_month = @course_begin_month
        WHERE student_id = _student_id AND point_subscription_id = _payment_id;
        
END";
  
        \DB::unprepared($procedure8);

        $procedure9 = "DROP PROCEDURE IF EXISTS `sp_disable_course_free`;
CREATE PROCEDURE `sp_disable_course_free`(IN _student_id INT)
BEGIN
SET @point_count = (SELECT SUM(point_count) FROM student_point_history where student_id = _student_id AND course_id = 1);
IF @point_count > 0 THEn
   INSERT INTO student_point_history(student_id,pay_date,pay_description,pay_type,point_count,expire_date,lesson_schedule_id,course_id,point_subscription_id)
              SELECT
                _student_id
                , NOW()
                ,'失効'
                ,5
                ,-1
                ,expire_date
                ,0
                ,1
            ,point_subscription_id
            FROM student_point_history WHERE student_id = _student_id AND course_id = 1 LIMIT 1;
              
END IF;
END";
  
        \DB::unprepared($procedure9);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_procedure_insert_payment_history');
    }
}
