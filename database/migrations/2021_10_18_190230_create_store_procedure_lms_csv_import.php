<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureLmsCsvImport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `lms_sp_csv_check_db`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `lms_sp_csv_check_db`(IN _student_id VARCHAR(255),
IN _student_email VARCHAR(255),
IN _project_code VARCHAR(255),
IN _course_id INT,
IN _corporation_flag VARCHAR(255),
IN _is_lms_user INT)
BEGIN
    SET @check_student_id_exist = (SELECT COUNT(id) AS ret FROM students WHERE id = _student_id AND is_lms_user = _is_lms_user);
    SET @check_mail_exist = (SELECT COUNT(id) AS ret FROM students WHERE student_email =  _student_email AND is_lms_user = _is_lms_user);
    SET @check_mail_and_id = (SELECT COUNT(id) AS ret FROM students WHERE id = _student_id AND student_email =  _student_email AND is_lms_user = _is_lms_user);
    SET @check_project = (SELECT id FROM lms_projects lp WHERE project_code = _project_code AND deleted_at IS NULL);
    SET @check_corporation = (SELECT id FROM lms_projects lp WHERE project_code = _project_code AND corporation_flag = _corporation_flag AND deleted_at IS NULL);
    
    SET @studentId = (SELECT id AS ret FROM students WHERE student_email =  _student_email AND is_lms_user = _is_lms_user LIMIT 1);
    SET @check_project_student_exist = COALESCE((SELECT 1 FROM lms_project_students lps WHERE lps.student_id = @studentId AND lps.project_id = @check_project AND lps.deleted_at IS NULL LIMIT 1),0);
    
    IF _course_id > 0 THEN
      SET @check_course_exist = (SELECT COUNT(course_id) FROM courses WHERE course_id = _course_id and is_set_course = 0);
      SET @check_project_course = (SELECT lpc.id FROM lms_project_courses lpc LEFT JOIN lms_projects lp ON lp.id = lpc.project_id WHERE lp.project_code = _project_code AND lpc.course_id = _course_id 
           AND lp.deleted_at IS NULL AND lpc.deleted_at IS NULL AND lpc.course_type = 0);
      SET @check_can_buy = COALESCE((SELECT lps.buy_course_flag FROM lms_project_students lps WHERE lps.student_id = _student_id AND lps.project_id = @check_project AND lps.deleted_at IS NULL LIMIT 1),1);
    ELSE
      SET @check_course_exist = 1;
      SET @check_project_course = 1;
      SET @check_can_buy = 1;
    END IF;
    
    SELECT    
     @check_mail_exist as check_mail_exist,
     @check_mail_and_id as check_mail_and_id,
     @check_project as check_project,
     @check_corporation as check_corporation,
     @check_course_exist as check_course_exist,
     @check_project_course as check_project_course,
     @check_student_id_exist as check_student_id_exist,
     @check_can_buy as check_can_buy,
     @studentId as student_id,
     @check_project_student_exist as check_project_student_exist
     ;
END";
        \DB::unprepared($procedure);

        $procedure1 = "DROP PROCEDURE IF EXISTS `sp_remind_mail_csv`;
        CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_remind_mail_csv`(IN _is_lms_user INT)
BEGIN
  SELECT
    srmp.mail_subject
        ,COALESCE(srmpe.mail_subject, srmp.mail_subject) AS mail_subject_en
        ,COALESCE(srmpv.mail_subject, srmp.mail_subject) AS mail_subject_vn
        ,srmp.mail_body
        ,COALESCE(srmpe.mail_body, srmp.mail_body) AS mail_body_en
        ,COALESCE(srmpv.mail_body, srmp.mail_body) AS mail_body_vn
    ,send_remind_mail_timing_type
  FROM
    send_remind_mail_patterns srmp
        LEFT JOIN send_remind_mail_pattern_infos srmpe 
            ON srmpe.send_remind_mail_pattern_id = srmp.id AND srmpe.lang_type = 'en'
        LEFT JOIN send_remind_mail_pattern_infos srmpv 
            ON srmpv.send_remind_mail_pattern_id = srmp.id AND srmpv.lang_type = 'vn'
  WHERE
        CASE WHEN _is_lms_user = 0 THEN
            send_remind_mail_timing_type IN (27,28)
        ELSE send_remind_mail_timing_type IN (29,30,31,32,44)
        END
  ;
END";
        \DB::unprepared($procedure1);

        $procedure2 = "DROP PROCEDURE IF EXISTS `lms_sp_import_student_csv`;
        CREATE DEFINER=`root`@`localhost` PROCEDURE `lms_sp_import_student_csv`(IN _student_first_name VARCHAR(255),
        IN _student_last_name VARCHAR(255),
        IN _student_first_kana VARCHAR(255),
        IN _student_last_kana VARCHAR(255),
        IN _student_nickname VARCHAR(255),
        IN _student_pw VARCHAR(16),
        IN _student_email VARCHAR(255),
        IN _student_skypename VARCHAR(255),
        IN _student_home_tel VARCHAR(255),
        IN _postcode VARCHAR(255),
        IN _prefecture_number INT,
        IN _student_address VARCHAR(500),
        IN _student_address1 VARCHAR(500),
        IN _student_address2 VARCHAR(500),
        IN _student_address3 VARCHAR(500),
        IN _department_name VARCHAR(255),
        IN _employee_number VARCHAR(255),
        IN _department_number VARCHAR(255),
        IN _project_code VARCHAR(255),
        IN _course_id INT,
        IN _course_begin_month VARCHAR(50),
        IN _received_order_date VARCHAR(20),
        IN _start_date VARCHAR(20),
        IN _begin_date VARCHAR(20),
        IN _expire_date VARCHAR(20),
        IN _management_number VARCHAR(100),
        IN _corporation_flag INT,
        IN _student_id INT,
        IN _lang_type VARCHAR(255),
        IN _time_zone INT)
root:BEGIN
   DECLARE update_flg INT DEFAULT 1; 
   DECLARE _order_id VARCHAR(32) DEFAULT NULL;
   DECLARE _project_id INT DEFAULT NULL;
   DECLARE _project_course_id INT DEFAULT NULL;
   DECLARE _expired_date VARCHAR(40) DEFAULT NULL;
   DECLARE _is_lms_user TINYINT DEFAULT 0;
   DECLARE _customer_code  VARCHAR(255) DEFAULT NULL;
   DECLARE _corporation_code VARCHAR(255) DEFAULT NULL;
   DECLARE _company_name VARCHAR(255) DEFAULT NULL;
   DECLARE _project_student_id INT DEFAULT 0;
   DECLARE _point_subscription_id INT DEFAULT 0;
   
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
          GET DIAGNOSTICS CONDITION 1
          @p2 = MESSAGE_TEXT;
          SELECT @p2 AS error,NULL, NULL, NULL, NULL, NULL, NULL, NULL;
          ROLLBACK;
    END;
    
   IF _student_id = 0  THEN
      SET _student_id = COALESCE(
            (SELECT id 
            FROM students 
            WHERE student_email = _student_email AND is_lms_user = 1
            LIMIT 1), 
            0);
      SET update_flg = IF(_student_id > 0, 1, 0);
   END IF;
   
   SET _project_id = (SELECT id FROM `lms_projects` WHERE `project_code` = _project_code AND deleted_at IS NULL);
   
   -- check data -- 
   IF _project_id IS NULL THEN
        COMMIT;
        SELECT 3 AS error, NULL, NULL, NULL, NULL, NULL, NULL, NULL;
    LEAVE root;
   END IF;
    
   SET _corporation_code = (SELECT lms_companies.legal_code FROM lms_projects LEFT JOIN lms_companies ON lms_projects.`company_id` = lms_companies.`id` WHERE lms_projects.id = _project_id);
   SET _customer_code = (SELECT lms_companies.favourite_code FROM lms_projects LEFT JOIN lms_companies ON lms_projects.`company_id` = lms_companies.`id` WHERE lms_projects.id = _project_id);
   SET _company_name = (SELECT lms_companies.company_name FROM lms_projects LEFT JOIN lms_companies ON lms_projects.`company_id` = lms_companies.`id` WHERE lms_projects.id = _project_id);
   
   IF _corporation_flag = 0 THEN
             SET _customer_code = NULL;
     END IF;
   
   IF _course_id <> 0 THEN
                        SELECT
            lpc.id,
            start_date_option
            INTO
            _project_course_id,
            @start_date_option
            FROM
            lms_project_courses lpc
            WHERE
            lpc.project_id = _project_id
            AND lpc.course_id = _course_id AND lpc.course_type = 0
            AND lpc.deleted_at IS NULL;
       IF _project_course_id IS NULL THEN
          COMMIT;
      SELECT 4 AS error,NULL, NULL, NULL, NULL, NULL, NULL, NULL;
      LEAVE root;
    END IF;
    
    SET @canBuyCourse = COALESCE(
        (SELECT lps.buy_course_flag 
        FROM lms_project_students lps 
        LEFT JOIN students st ON st.id = lps.student_id 
        WHERE lps.student_id = _student_id AND lps.project_id = _project_id 
        AND lps.deleted_at IS NULL LIMIT 1)
        ,1);
    
    IF @canBuyCourse <> 1 THEN
          COMMIT;
      SELECT 5 AS error,NULL, NULL, NULL, NULL, NULL, NULL, NULL;
      LEAVE root;
    END IF;
    
   END IF;
   SET _is_lms_user = 1;
   
   -- insert data to table user by Tung--
   IF _student_id <> 0 THEN
    IF _department_name <> '' THEN 
    UPDATE students SET department_name = _department_name WHERE id = _student_id;
    END IF;
    IF _employee_number <>  '' THEN 
    UPDATE students SET employee_number = _employee_number WHERE id = _student_id;
    END IF;
    IF _department_number <> '' THEN 
    UPDATE students SET department_number = _department_number WHERE id = _student_id;
    END IF;
    UPDATE students SET lang_type = _lang_type WHERE id = _student_id;
    UPDATE students SET timezone_id = _time_zone WHERE id = _student_id;
    END IF;
   IF update_flg = 0 THEN
        CALL lms_sp_insert_new_student_via_csv(
                                     _student_first_name,
                                     _student_last_name,
                                     _student_first_kana,
                                     _student_last_kana,
                                     _student_email,
                                    CONCAT('alc',_student_pw,'alc'),
                                     _student_nickname,
                                     _student_skypename,
                                     NULL,
                                     0,
                                     '',
                                     _company_name,
                                     _student_home_tel,
                                     '',
                                    _postcode,
                                    _prefecture_number,
                                    _student_address,
                                    _student_address1,
                                    _student_address2,
                                    _student_address3,
                                    _department_name,
                                    _employee_number,
                                    _department_number,
                                    _project_id,
                                    _is_lms_user,
                                    _lang_type,
                                    _time_zone
                                     );
   END IF;
   IF _student_id = 0 THEN
      SET _student_id = (SELECT id FROM students WHERE student_email = _student_email AND is_lms_user = 1);
   END IF;
   
   -- insert lms_project_student
   SET _project_student_id = (SELECT COUNT(lps.id) FROM lms_project_students lps LEFT JOIN students st ON st.id = lps.student_id WHERE lps.student_id = _student_id AND lps.project_id = _project_id AND lps.deleted_at IS NULL);
   IF _project_student_id = 0 THEN
    INSERT INTO lms_project_students(
        project_id,
        company_id,
        student_id,
    department_name,
    employee_number,
    department_number,
        created_at,
        updated_at,
        deleted_at
        )
        VALUES (
        _project_id,
        (SELECT company_id FROM lms_projects WHERE id = _project_id AND deleted_at IS NULL),
        _student_id,
      _department_name,
      _employee_number,
      _department_number,
         NOW(),
         NOW(),
         NULL
        );
   ELSE
      
       IF _department_name <> '' THEN 
       UPDATE lms_project_students SET department_name = _department_name WHERE student_id = _student_id AND  project_id = _project_id;
       END IF;
       IF _employee_number <>  '' THEN 
       UPDATE lms_project_students SET employee_number = _employee_number WHERE student_id = _student_id AND  project_id = _project_id ;
       END IF;
       IF _department_number <> '' THEN 
       UPDATE lms_project_students SET department_number = _department_number WHERE student_id = _student_id AND  project_id = _project_id;
       END IF;
   END IF;
   
   IF _course_id <> 0 THEN
      
      
      SET @point_expire_day = (SELECT point_expire_day FROM courses WHERE course_id=_course_id);
      SET @score_grace_period = (SELECT COALESCE(score_grace_period, 0) FROM lms_project_courses WHERE id = _project_course_id);   
      
      IF @start_date_option = 3 THEN
        SET _expired_date = IF(_expire_date = '', DATE_FORMAT(_begin_date,'%Y-%m-%d 23:59:59') + INTERVAL @point_expire_day DAY + INTERVAL @score_grace_period MONTH ,DATE_FORMAT(_expire_date,'%Y-%m-%d 23:59:59'));
      ELSE
        SET _expired_date =  IF(_expire_date = '',(DATE_FORMAT(CONCAT(_course_begin_month, '01'), '%Y-%m-%d %H:%i:%s') + INTERVAL (CEIL(@point_expire_day/ 30) + @score_grace_period) MONTH - INTERVAL 1 SECOND), DATE_FORMAT(_expire_date,'%Y-%m-%d 23:59:59'));
      END IF;
      
      SET @amount = (SELECT price FROM lms_project_courses WHERE id = _project_course_id) ;
      CALL sp_create_order_id_alc_format(_student_id, _order_id);
      CALL sp_insert_point_subscription_lms_csv(
                   _project_course_id,
                   _project_id,
                                   _student_id,
                                   _course_id,
                                   0,
                                   @amount,
                                   ROUND(@amount*0.1),
                                   'JPY',
                                   _corporation_flag, -- 法人請求:CSV, 個人請求:GMO
                                   DATE_FORMAT(_received_order_date,'%Y/%m/%d'),
                                   1,
                                   '',
                                   '',
                                   _order_id,
                                   _customer_code,
                                   _corporation_code,
                                   _received_order_date,
                                   _start_date,
                                   _begin_date,
                                   _expired_date,
                                   DATE_FORMAT(CONCAT(_course_begin_month, '01'), '%Y-%m-%d'),
                                   _management_number
                                   ) ;
      
      SET _point_subscription_id = (SELECT MAX(id) FROM point_subscription_histories WHERE student_id = _student_id);
      SET @campaign_code = (SELECT campaign_code FROM courses WHERE course_id = _course_id);
   
   
      CALL sp_admin_insert_order(
    _order_id,
    _student_id,
    NULL,
    _course_id,
    '00000100',
    @campaign_code,
    1,
    'CSV IMPORT',
    NULL ,
    3,
     _corporation_code,
     _received_order_date
      );
   END IF;
   
   COMMIT;
   
     SELECT update_flg AS update_flg,
           s.student_name AS student_name,
           psh.id AS point_subscription_history_id,
           COALESCE(ci.course_name, c.course_name) AS course_name,
           psh.amount AS amount,
           psh.tax AS tax,
           psh.point_expire_date AS expire_date,
           psh.order_id AS order_id,
           s.lang_type AS lang_type,
                     s.postcode AS postcode,
                     lp.prefecture_name AS prefecture_name,
                     s.student_address AS student_address,
                     s.student_address1 AS student_address1,
                     s.student_address2 AS student_address2,
                     s.student_address3 AS student_address3
     FROM students s
     LEFT JOIN point_subscription_histories psh ON psh.id = _point_subscription_id AND s.id = psh.student_id
     LEFT JOIN courses c ON c.course_id = psh.course_id
         LEFT JOIN course_infos ci ON ci.course_id = c.course_id AND ci.lang_type = IF(s.lang_type = 3, 'vn', IF(s.lang_type = 2, 'en', 'jp'))
         LEFT JOIN lms_prefectures AS lp ON lp.id = s.prefecture_number
     WHERE s.id = _student_id
     LIMIT 1
      ;
END";
        \DB::unprepared($procedure2);

        $procedure3 = "DROP PROCEDURE IF EXISTS `lms_sp_insert_new_student_via_csv`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `lms_sp_insert_new_student_via_csv`(IN _student_first_name VARCHAR(255),
IN _student_last_name VARCHAR(255),
IN _student_first_kana VARCHAR(255),
IN _student_last_kana VARCHAR(255),
IN _student_email VARCHAR(255),
IN _student_password VARCHAR(255),
IN _student_nickname VARCHAR(255),
IN _student_skypename VARCHAR(255),
IN _expire_date DATETIME ,
IN _student_type INT,
IN _default_student_image_path VARCHAR(255),
IN _company_name VARCHAR(255),
IN _student_home_tel VARCHAR(255),
IN _student_company_tel VARCHAR(255),
IN _postcode VARCHAR(255),
IN _prefecture_number INT,
IN _student_address VARCHAR(255),
IN _student_address1 VARCHAR(255),
IN _student_address2 VARCHAR(255),
IN _student_address3 VARCHAR(255),
IN _department_name VARCHAR(255),
IN _employee_number VARCHAR(255),
IN _department_number VARCHAR(255),
IN _project_id INT,
IN _is_lms_user TINYINT,
IN _lang_type VARCHAR(255),
IN _time_zone INT)
BEGIN
    
    
    INSERT INTO students (
        id
        ,student_name
    ,student_first_name
    ,student_last_name
        ,student_first_name_kata
        ,student_last_name_kata
        ,student_name_kana
        ,student_email
        ,password
        ,student_nickname
        ,student_skypename
        ,student_introduction
        ,photo_savepath
        ,last_login_at
        ,is_tmp_entry
        ,remember_token
        ,remember_token_expires_at
        ,student_type
        ,created_at
        ,lang_type
        ,course_id
        ,course_update_count
        ,timezone_id
        ,is_sending_dm
        ,company_name
        ,student_home_tel,
        student_company_tel,
        postcode,
        prefecture_number,
        student_address,
        student_address1,
        student_address2,
        student_address3,
        department_name,
        employee_number,
        department_number,
        is_lms_user
    ) VALUES (
        NULL
        ,CONCAT(_student_first_name, ' ', _student_last_name)
                ,_student_first_name
                ,_student_last_name
        ,_student_first_kana
        ,_student_last_kana
        ,CONCAT(_student_first_kana, ' ', _student_last_kana)
        ,_student_email
        ,MD5(_student_password)
        ,_student_nickname
        ,_student_skypename
        ,''
        ,''
        ,NULL
        ,0
        ,'0'
        ,DATE_ADD( NOW(),INTERVAL (SELECT tmp_student_expire_time FROM system_settings) HOUR)
        ,_student_type
        , NOW()
        ,_lang_type
        ,1
        ,0
        ,_time_zone
        ,1
       ,_company_name
       ,_student_home_tel,
       _student_company_tel,
       _postcode,
       _prefecture_number,
       _student_address,
       _student_address1,
       _student_address2,
       _student_address3,
       _department_name,
       _employee_number,
       _department_number,
       _is_lms_user
    );
    
END";
        \DB::unprepared($procedure3);

          $procedure4 = "DROP PROCEDURE IF EXISTS `sp_create_order_id_alc_format`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_order_id_alc_format`(IN _student_id INT, OUT _order_id VARCHAR(32))
BEGIN
    DECLARE _order_id_temp VARCHAR(32) DEFAULT NULL;
    DECLARE _random_number VARCHAR(1) DEFAULT NULL;
    
    SET _order_id_temp = (SELECT CONCAT(_student_id,'alc', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s')));
    WHILE (LENGTH(_order_id_temp) < 27) DO
        SET _random_number = FLOOR(RAND() * 10);
        SET _order_id_temp := CONCAT(_random_number, _order_id_temp);
    END WHILE;
    SELECT _order_id_temp INTO _order_id;
END";

        \DB::unprepared($procedure4);

          $procedure5 = "DROP PROCEDURE IF EXISTS `sp_insert_point_subscription_lms_csv`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_point_subscription_lms_csv`(IN _project_course_id INT,
IN _project_id INT,
IN _student_id INT,
IN _course_id INT,
IN _set_course_id INT,
IN _amount VARCHAR(45),
IN _tax VARCHAR(45),
IN _currency_unit VARCHAR(45),
IN _payment_way  VARCHAR(1),
IN _payment_date   VARCHAR(255),
IN _payment_status VARCHAR(255),
IN _first_name     VARCHAR(255),
IN _last_name      VARCHAR(255),
IN _order_id       VARCHAR(27),
IN _customer_code VARCHAR (255),
IN _corporation_code  VARCHAR(255),
IN _update_date VARCHAR(20),
IN _start_date VARCHAR(20),
IN _begin_date VARCHAR(20),
IN _expire_date VARCHAR(20),
IN _start_date_origin VARCHAR(20),
IN _management_number VARCHAR(100))
BEGIN
    DECLARE _rtn INT DEFAULT 0;
    DECLARE _add_point INT DEFAULT 0;
    
    DECLARE _default_customer_code VARCHAR(255) DEFAULT NULL;
    DECLARE _default_corporation_code VARCHAR(255) DEFAULT NULL;
        DECLARE _corporation_flag tinyint DEFAULT NULL;
    
    SELECT lc.favourite_code,lc.legal_code INTO _default_customer_code, _default_corporation_code FROM lms_companies lc LEFT JOIN lms_projects lp ON lc.id = lp.company_id WHERE lp.id = _project_id;
    SELECT corporation_flag INTO _corporation_flag FROM lms_projects lp WHERE lp.id = _project_id;
    IF _corporation_flag = 0 THEN
             SET _default_customer_code = NULL;
        END IF;
    INSERT INTO point_subscription_histories(
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
         _payment_status,
         IF(_update_date = '',  NOW(), _update_date),
         _order_id,
         (SELECT course_name FROM courses WHERE course_id = _course_id),
         (SELECT point_count FROM courses WHERE course_id = _course_id),
         _expire_date,
         (SELECT paypal_item_number FROM courses WHERE course_id = _course_id),
         IF(_customer_code = '' OR _customer_code IS NULL, _default_customer_code, _customer_code),
         IF(_corporation_code = '' OR _corporation_code IS NULL, _default_corporation_code, _corporation_code),
         _begin_date,
         _management_number
    );
CASE
  WHEN _payment_way IN (0,1) THEN
    
    SET   _add_point = COALESCE((SELECT point_count FROM courses WHERE course_id = _course_id),0);
    CASE  _add_point
      WHEN 0 THEN
        SET _rtn = 1;
      ELSE
          
          -- Process with course free
          CALL sp_disable_course_free(_student_id);
          
          INSERT INTO student_point_histories
            (student_id,pay_date,pay_description,pay_type,point_count,expire_date,lesson_schedule_id,course_id,start_date,point_subscription_id)
            VALUES
             (
               _student_id
               ,NOW()
              
        ,CONCAT('レッスン付与 (',(SELECT course_name FROM courses WHERE course_id = _course_id),')')
               ,_payment_way
                     ,_add_point
               ,_expire_date
               ,0
                     ,_course_id
                     ,_start_date
         ,(SELECT MAX(id) FROM point_subscription_histories WHERE student_id = _student_id)
             );
        CALL lms_insert_project_course_student(_project_course_id, _project_id, _course_id, _student_id, _start_date, _expire_date, _start_date_origin);
    END CASE;
    UPDATE students
     SET
       course_id = IF(_set_course_id = 0, _course_id, _set_course_id)
       ,course_update_count = course_update_count + 1
     WHERE
       id = _student_id;
    
  ELSE
    SET _rtn = 1;
    
END CASE;
END";
        \DB::unprepared($procedure5);

        $procedure6 = "DROP PROCEDURE IF EXISTS `lms_sp_csv_check_db_setcourse`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `lms_sp_csv_check_db_setcourse`(IN _student_id VARCHAR(255),
IN _student_email VARCHAR(255),
IN _project_code VARCHAR(255),
IN _course_id INT,
IN _corporation_flag VARCHAR(255),
IN _is_lms_user INT)
BEGIN
    SET @student_id = (SELECT id AS ret FROM students WHERE student_email =  _student_email AND is_lms_user = _is_lms_user LIMIT 1);
    SET @check_student_id_exist = (SELECT COUNT(id) AS ret FROM students WHERE id = _student_id AND is_lms_user = _is_lms_user LIMIT 1);
    SET @check_mail_exist = (SELECT COUNT(id) AS ret FROM students WHERE student_email =  _student_email AND is_lms_user = _is_lms_user LIMIT 1);
    SET @check_mail_and_id = (SELECT COUNT(id) AS ret FROM students WHERE id = _student_id AND student_email =  _student_email AND is_lms_user = _is_lms_user LIMIT 1);
    SET @check_project = (SELECT id FROM lms_projects lp WHERE project_code = _project_code AND deleted_at IS NULL LIMIT 1);
    SET @check_corporation = (SELECT id FROM lms_projects lp WHERE project_code = _project_code AND corporation_flag = _corporation_flag AND deleted_at IS NULL LIMIT 1);
    
    IF _course_id > 0 THEN
      SET @check_course_exist = (SELECT COUNT(course_id) FROM courses WHERE course_id = _course_id AND is_set_course = 1 LIMIT 1) ;
      SET @check_project_course = (SELECT lpc.id FROM lms_project_courses lpc LEFT JOIN lms_projects lp ON lp.id = lpc.project_id WHERE lp.project_code = _project_code 
                    AND lpc.course_id = _course_id AND lpc.course_type = 1 AND  lp.deleted_at IS NULL AND lpc.deleted_at IS NULL LIMIT 1);
      SET @check_can_buy = COALESCE((SELECT lps.buy_course_flag FROM lms_project_students lps WHERE lps.student_id = _student_id AND lps.project_id = @check_project AND lps.deleted_at IS NULL LIMIT 1),1);
    ELSE
      SET @check_course_exist = 1;
      SET @check_project_course = 1;
      SET @check_can_buy = 1;
    END IF;
    
    SELECT
     @student_id AS student_id,
     @check_mail_exist AS check_mail_exist,
     @check_mail_and_id AS check_mail_and_id,
     @check_project AS check_project,
     @check_corporation AS check_corporation,
     @check_course_exist AS check_course_exist,
     @check_project_course AS check_project_course,
     @check_student_id_exist AS check_student_id_exist,
     @check_can_buy AS check_can_buy
     ;
END";
        \DB::unprepared($procedure6);

        $procedure7 = "DROP PROCEDURE IF EXISTS `lms_sp_import_student_csv_setcourse`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `lms_sp_import_student_csv_setcourse`(IN _student_first_name VARCHAR(255),
  IN _student_last_name VARCHAR(255),
  IN _student_first_kana VARCHAR(255),
  IN _student_last_kana VARCHAR(255),
  IN _student_nickname VARCHAR(255),
  IN _student_pw VARCHAR(16),
  IN _student_email VARCHAR(255),
  IN _student_skypename VARCHAR(255),
  IN _student_home_tel VARCHAR(255),
  IN _postcode VARCHAR(255),
  IN _prefecture_number INT,
  IN _student_address VARCHAR(500),
  IN _student_address1 VARCHAR(500),
  IN _student_address2 VARCHAR(500),
  IN _student_address3 VARCHAR(500),
  IN _department_name VARCHAR(255),
  IN _employee_number VARCHAR(255),
  IN _department_number VARCHAR(255),
  IN _project_code VARCHAR(255),
  IN _course_id INT,
  IN _received_order_date VARCHAR(255),
  IN _student_id INT,
  IN _lang_type VARCHAR(255),
  IN _time_zone INT)
root:BEGIN
   DECLARE update_flg INT DEFAULT 1; 
   DECLARE _order_id VARCHAR(32) DEFAULT NULL;
   DECLARE _project_id INT DEFAULT NULL;
   DECLARE _project_course_id INT DEFAULT NULL;
   DECLARE _corporation_code VARCHAR(255) DEFAULT NULL;
   DECLARE _expired_date VARCHAR(40) DEFAULT NULL;
   DECLARE _company_name VARCHAR(255) DEFAULT NULL;
   DECLARE _project_student_id INT DEFAULT 0;

   DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
          SELECT 'error' AS error;
          ROLLBACK;
    END; 
    
    
   IF _student_id = 0  THEN
      SET _student_id = COALESCE((SELECT id FROM students WHERE student_email = _student_email AND is_lms_user = 1 LIMIT 1) , 0);
      SET update_flg = IF(_student_id > 0, 1, 0);
   END IF;
   
   SET _project_id = (SELECT id FROM `lms_projects` WHERE `project_code` = _project_code AND deleted_at IS NULL);
   
   -- check data -- 
   IF _project_id IS NULL THEN
        COMMIT;
        SELECT 3 AS error;
  LEAVE root;
   END IF;
   
   SET _corporation_code = (SELECT lms_companies.legal_code FROM lms_projects LEFT JOIN lms_companies ON lms_projects.`company_id` = lms_companies.`id` WHERE lms_projects.id = _project_id); 
   SET _company_name = (SELECT lms_companies.company_name FROM lms_projects LEFT JOIN lms_companies ON lms_projects.`company_id` = lms_companies.`id` WHERE lms_projects.id = _project_id);
   
   IF _course_id <> 0 THEN
       SET _project_course_id = (SELECT
      lpc.id
      FROM
      lms_project_courses lpc
      WHERE
      lpc.project_id = _project_id
      AND lpc.course_id = _course_id
      AND lpc.deleted_at IS NULL LIMIT 1);
       IF _project_course_id IS NULL THEN
          COMMIT;
    SELECT 4 AS error,NULL, NULL;
    LEAVE root;
  END IF;
  
  SET @canBuyCourse = COALESCE((SELECT lps.buy_course_flag FROM lms_project_students lps WHERE lps.student_id = _student_id AND lps.project_id = _project_id AND deleted_at IS NULL LIMIT 1),1);
  
  IF @canBuyCourse <> 1 THEN
          COMMIT;
    SELECT 5 AS error;
    LEAVE root;
  END IF;
  
   END IF;
   
   -- insert data to table user by Tung--
   IF _student_id <> 0 THEN
  IF _department_name <> '' THEN 
  UPDATE students SET department_name = _department_name WHERE id = _student_id;
  END IF;
  IF _employee_number <>  '' THEN 
  UPDATE students SET employee_number = _employee_number WHERE id = _student_id;
  END IF;
  IF _department_number <> '' THEN 
  UPDATE students SET department_number = _department_number WHERE id = _student_id;
  END IF;
  UPDATE students SET lang_type = _lang_type WHERE id = _student_id;
  UPDATE students SET timezone_id = _time_zone WHERE id = _student_id;
    END IF;
    
   IF update_flg = 0 THEN
        CALL lms_sp_insert_new_student_via_csv(
             _student_first_name,
             _student_last_name,
             _student_first_kana,
             _student_last_kana,
             _student_email,
            CONCAT('alc',_student_pw,'alc'),
             _student_nickname,
             _student_skypename,
             NULL,
             0,
             '',
             _company_name,
             _student_home_tel,
             '',
            _postcode,
            _prefecture_number,
            _student_address,
            _student_address1,
            _student_address2,
            _student_address3,
            _department_name,
            _employee_number,
            _department_number,
            _project_id,
            1,
            _lang_type,
            _time_zone);
   END IF;
   IF _student_id = 0 THEN
      SET _student_id = (SELECT id FROM students WHERE student_email = _student_email AND is_lms_user =1);
   END IF;
   
   -- insert lms_project_student
   SET _project_student_id = (SELECT COUNT(id) FROM lms_project_students WHERE student_id = _student_id AND project_id = _project_id AND deleted_at IS NULL);
   IF _project_student_id = 0 THEN
  INSERT INTO lms_project_students(
    project_id,
    company_id,
    student_id,
    department_name,
    employee_number,
    department_number,
    created_at,
    updated_at,
    deleted_at
      )
      VALUES (
      _project_id,
      (SELECT company_id FROM lms_projects WHERE id = _project_id AND deleted_at IS NULL),
      _student_id,
      _department_name,
      _employee_number,
      _department_number,
       NOW(),
       NOW(),
      NULL
      );
    ELSE
       IF _department_name <> '' THEN 
       UPDATE lms_project_students SET department_name = _department_name WHERE student_id = _student_id AND  project_id = _project_id;
       END IF;
       IF _employee_number <>  '' THEN 
       UPDATE lms_project_students SET employee_number = _employee_number WHERE student_id = _student_id AND  project_id = _project_id ;
       END IF;
       IF _department_number <> '' THEN 
       UPDATE lms_project_students SET department_number = _department_number WHERE student_id = _student_id AND  project_id = _project_id;
       END IF;
   END IF;
   
   IF _course_id <> 0 THEN
      
      CALL sp_create_order_id_alc_format(_student_id, _order_id);
      
      SET @campaign_code = (SELECT campaign_code FROM courses WHERE course_id = _course_id);
   
   
      CALL sp_admin_insert_order(
  _order_id,
  _student_id,
  NULL,
  _course_id,
  '00000100',
  @campaign_code,
  1,
  'CSV IMPORT',
  NULL ,
  3,
   _corporation_code,
   _received_order_date
      );
   END IF;
   
   COMMIT;
     SET @lang_type = (SELECT lang_type FROM students WHERE id = _student_id LIMIT 1);
     SELECT update_flg,
           _student_id AS student_id,
           (SELECT COALESCE(ci.course_name, c.course_name) FROM courses c
           LEFT JOIN course_infos ci ON ci.course_id = c.course_id 
            AND ci.lang_type = IF(@lang_type = 3, 'vn', IF(@lang_type = 2, 'en', 'jp'))
           WHERE c.course_id = _course_id LIMIT 1) AS set_course_name,
           _order_id AS order_id,
           _project_id AS project_id,
           s.student_name,
           s.lang_type,
           s.postcode AS postcode,
           lp.prefecture_name AS prefecture_name,
           s.student_address AS student_address,
           s.student_address1 AS student_address1,
           s.student_address2 AS student_address2,
           s.student_address3 AS student_address3
     FROM students s 
     LEFT JOIN lms_prefectures AS lp ON lp.id = s.prefecture_number
     WHERE s.id = _student_id
      ;
END";
        \DB::unprepared($procedure7);

        $procedure8 = "DROP PROCEDURE IF EXISTS `sp_insert_point_subscription_lms_csv_setcourse`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_point_subscription_lms_csv_setcourse`(IN _project_id INT,
IN _student_id INT,
IN _course_id INT,
IN _set_course_id INT,
IN _order_id       VARCHAR(255),
IN _payment_date   VARCHAR(255),
IN _start_date VARCHAR(20),
IN _begin_date VARCHAR(20),
IN _expire_date VARCHAR(20),
IN _start_date_origin VARCHAR(20),
IN _management_number VARCHAR(100),
IN _corporation_flag INT)
BEGIN
    DECLARE _project_course_id INT DEFAULT NULL;
    DECLARE _customer_code  VARCHAR(255) DEFAULT NULL;
    DECLARE _corporation_code VARCHAR(255) DEFAULT NULL;
    DECLARE _point_subscription_id INT DEFAULT 0;
     DECLARE EXIT HANDLER FOR SQLEXCEPTION 
     BEGIN
          SELECT 'error' AS error;
          ROLLBACK;
     END;
    
                SELECT
      lpc.id,
      start_date_option
      INTO
      _project_course_id,
      @start_date_option
      FROM
      lms_project_courses lpc
      WHERE
      lpc.project_id = _project_id
      AND lpc.course_id = _course_id
      AND lpc.set_course_id = _set_course_id
      AND lpc.deleted_at IS NULL;
      
    SET _corporation_code = (SELECT lms_companies.legal_code FROM lms_projects LEFT JOIN lms_companies ON lms_projects.`company_id` = lms_companies.`id` WHERE lms_projects.id = _project_id);
    SET _customer_code = (SELECT lms_companies.favourite_code FROM lms_projects LEFT JOIN lms_companies ON lms_projects.`company_id` = lms_companies.`id` WHERE lms_projects.id = _project_id);
    SET @point_expire_day = (SELECT point_expire_day FROM courses WHERE course_id=_course_id);
    SET @score_grace_period = (SELECT COALESCE(score_grace_period, 0) FROM lms_project_courses WHERE id = _project_course_id);   
    
    IF _corporation_flag = 0 THEN
       SET _customer_code = NULL;
    END IF;
    
    IF @start_date_option = 3 THEN
        SET _expire_date = IF(_expire_date = '', DATE_FORMAT(_begin_date,'%Y-%m-%d 23:59:59') + INTERVAL @point_expire_day DAY + INTERVAL @score_grace_period MONTH ,DATE_FORMAT(_expire_date,'%Y-%m-%d 23:59:59'));
    ELSE
        SET _expire_date =  IF(_expire_date = '',(DATE_FORMAT(CONCAT(_start_date_origin, '01'), '%Y-%m-%d %H:%i:%s') + INTERVAL (CEIL(@point_expire_day/ 30) + @score_grace_period) MONTH - INTERVAL 1 SECOND), DATE_FORMAT(_expire_date,'%Y-%m-%d 23:59:59'));
    END IF;
        
    SET @amount = (SELECT price FROM lms_project_courses WHERE id = _project_course_id) ;
    
    CALL sp_insert_point_subscription_lms_csv(
           _project_course_id,
           _project_id,
           _student_id,
           _course_id,
           _set_course_id,
           @amount,
           ROUND(@amount*0.1),
           'JPY',
           _corporation_flag, -- 法人請求:CSV, 個人請求:GMO
           DATE_FORMAT(_payment_date,'%Y/%m/%d'),
           1,
           '',
           '',
           _order_id,
           _customer_code,
           _corporation_code,
           _payment_date,
           _start_date,
           _begin_date,
           _expire_date,
           DATE_FORMAT(CONCAT(_start_date_origin, '01'), '%Y-%m-%d'),
           _management_number
           ) ;
     SET _point_subscription_id = (SELECT MAX(id) FROM point_subscription_histories WHERE student_id = _student_id);
     COMMIT;
     SET @lang_type = (SELECT lang_type FROM students WHERE id = _student_id LIMIT 1);
     SELECT
           psh.id,
           COALESCE(ci.course_name, c.course_name) AS course_name,
           c.course_id,
           psh.amount,
           psh.tax,
           psh.point_expire_date
     FROM point_subscription_histories psh
     LEFT JOIN courses c ON c.course_id = psh.course_id
     LEFT JOIN course_infos ci ON ci.course_id = c.course_id AND ci.lang_type = IF(@lang_type = 3, 'vn', IF(@lang_type = 2, 'en', 'jp'))
     WHERE psh.id = _point_subscription_id
     LIMIT 1;
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
        Schema::dropIfExists('lms_sp_csv_check_db');
    }
}
