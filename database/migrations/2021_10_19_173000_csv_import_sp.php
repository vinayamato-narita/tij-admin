<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CsvImportSp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_csv_check_db`(IN _student_id VARCHAR(255),
IN _student_email VARCHAR(255),
IN _course_id INT,
IN _is_lms_user INT)
BEGIN
    SET @check_student_id_exist = (SELECT COUNT(id) AS ret FROM students WHERE id = _student_id AND is_lms_user = _is_lms_user );
    SET @check_mail_exist = (SELECT COUNT(id) AS ret FROM students WHERE student_email =  _student_email AND is_lms_user = _is_lms_user );
    SET @check_mail_and_id = (SELECT COUNT(id) AS ret FROM students WHERE id = _student_id AND student_email =  _student_email AND is_lms_user = _is_lms_user );
    SET @check_course_exist = (SELECT COUNT(course_id) FROM courses WHERE course_id = _course_id AND is_set_course = 0 ) ;
    
    SELECT    
     @check_mail_exist,
     @check_mail_and_id,
     @check_course_exist,
     @check_student_id_exist
     ;
END";
        \DB::unprepared($procedure1);


        $procedure2 = "CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_point_subscription_csv`(IN _student_id INT,
    IN _course_id INT,
    IN _set_course_id INT,
    IN _amount VARCHAR(45),
    IN _tax VARCHAR(45),
    IN _currency_unit VARCHAR(45),
    IN _payment_way  VARCHAR(1),
    IN _start_date VARCHAR(255),
    IN _payment_status VARCHAR(255),
    IN _order_id VARCHAR(27),
    IN _customer_code VARCHAR (255),
    IN _corporation_code  VARCHAR(255),
    IN _payment_date VARCHAR(20),
    IN _expired_date VARCHAR(20),
    IN _management_number VARCHAR(20))
BEGIN
    DECLARE _rtn INT DEFAULT 0;
    DECLARE _add_point INT DEFAULT 0;
    
    SET @expired_date = IF(_expired_date = '',(SELECT DATE_ADD(IF(_start_date = '',  NOW(), DATE_FORMAT(_start_date,'%Y-%m-%d 23:59:59')), INTERVAL c.point_expire_day DAY) FROM courses c WHERE c.course_id = _course_id), DATE_FORMAT(_expired_date,'%Y-%m-%d 23:59:59'));
    
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
         IF(_payment_date = '',  NOW(), _payment_date),
         _order_id,
         (SELECT course_name FROM courses WHERE course_id = _course_id),
         (SELECT point_count FROM courses WHERE course_id = _course_id),
         @expired_date,
         (SELECT paypal_item_number FROM courses WHERE course_id = _course_id),
         _customer_code,
         _corporation_code
         ,IF(_start_date = '',  NOW(), DATE_FORMAT(_start_date,'%Y-%m-%d %H:%i:%s'))
         ,_management_number
    );
CASE
  WHEN _payment_way IN (0,1) THEN
    
    SET   _add_point = COALESCE((SELECT point_count FROM courses WHERE course_id = _course_id),0);
    CASE  _add_point
      WHEN 0 THEN
        SET _rtn = 1;
      ELSE
          -- Process with course free
	call sp_disable_course_free(_student_id);    
         
          INSERT INTO student_point_histories
          	(student_id,pay_date,pay_description,pay_type,point_count,expire_date,lesson_schedule_id,course_id,start_date,point_subscription_id)
	          VALUES
	           (
	             _student_id
	             ,NOW()
	            
		     ,CONCAT('レッスン付与 (',(SELECT course_name FROM courses WHERE course_id = _course_id),')')
	             ,_payment_way
                     ,_add_point
	             ,@expired_date
	             ,0
                     ,_course_id
                     ,IF(_start_date = '',  NOW(), DATE_FORMAT(_start_date,'%Y-%m-%d %H:%i:%s'))
				 ,(SELECT MAX(id) FROM point_subscription_histories WHERE student_id = _student_id)
	           );
        
    END CASE;
    UPDATE students
     SET
       course_id = IF(_set_course_id = 0, _course_id, _set_course_id)
       ,course_update_count = course_update_count + 1
     WHERE
       id = _student_id
    ;
  ELSE
    SET _rtn = 1;
END CASE;
END";
        DB::unprepared($procedure2);

        $procedure3 = "CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_new_student_via_csv`(IN _student_firstname VARCHAR(255),
    IN _student_lastname VARCHAR(255),
    IN _student_name VARCHAR(255),
    IN _student_email VARCHAR(255),
    IN _student_password VARCHAR(255),
    IN _student_nickname VARCHAR(255),
    IN _timezone_id VARCHAR(20),
    IN _student_skypename VARCHAR(255),
    IN _expire_date DATETIME ,
    IN _student_type INT,
    IN _default_student_image_path VARCHAR(255),
    IN _lang_type INT,
    IN _company_name VARCHAR(255))
BEGIN   
    
    INSERT INTO students (
        id
        ,student_first_name
        ,student_last_name
        ,student_name
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
        ,create_date
        ,lang_type
        ,course_id
        ,course_update_count
        ,timezone_id
        ,is_sending_dm
        ,direct_mail_flag
        ,company_name
    ) VALUES (
        NULL
        ,_student_firstname
        ,_student_lastname
        ,_student_name
        ,_student_email
        ,MD5(_student_password)
        ,_student_nickname
        ,_student_skypename
        ,\"\"
        ,\"\"
        ,NULL
        ,0
        ,\"0\"
        ,DATE_ADD( NOW(),INTERVAL (SELECT tmp_student_expire_time FROM system_settings) HOUR)
        ,_student_type
        , NOW()
        ,_lang_type
        ,1
        ,0
        ,_timezone_id
        ,1 -- 督促メール
        ,IF(_company_name IS NULL OR _company_name = '',1,0) -- 法人名がない場合、DM送信する、逆にDM送信しない
       ,_company_name
    );
    
    SET @new_student_id = (SELECT COALESCE(MAX(id),0) FROM students WHERE student_email = _student_email);    
    
END";

        DB::unprepared($procedure3);


        $procedure4 = "CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_order_id_alc_format`(IN _student_id INT, OUT _order_id VARCHAR(32))
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
        DB::unprepared($procedure4);


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
