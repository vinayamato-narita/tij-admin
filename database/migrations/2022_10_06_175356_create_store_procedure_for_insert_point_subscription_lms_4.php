<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForInsertPointSubscriptionLms4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_insert_point_subscription_lms`;
        CREATE PROCEDURE `sp_insert_point_subscription_lms`(IN _project_course_id INT,
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
        IN _start_date_origin VARCHAR(20))
        BEGIN
            DECLARE _rtn INT DEFAULT 0;
            DECLARE _add_point INT DEFAULT 0;

            DECLARE _default_customer_code VARCHAR(255) DEFAULT NULL;
            DECLARE _default_corporation_code VARCHAR(255) DEFAULT NULL;
            DECLARE _corporation_flag TINYINT DEFAULT NULL;

            SET @expired_date = IF(_expire_date = '',(SELECT DATE_ADD(IF(_start_date = '',  NOW(), DATE_FORMAT(_start_date,'%Y-%m-%d 23:59:59')), INTERVAL c.expire_day DAY) FROM course c WHERE c.course_id = _course_id), DATE_FORMAT(_expire_date,'%Y-%m-%d 23:59:59'));

            SELECT lc.favourite_code,lc.legal_code INTO _default_customer_code, _default_corporation_code FROM lms_company lc LEFT JOIN lms_project lp ON lc.company_id = lp.company_id WHERE lp.project_id = _project_id;
            SELECT corporation_flag INTO _corporation_flag FROM lms_project lp WHERE lp.project_id = _project_id;
            IF _corporation_flag = 0 THEN
                     SET _default_customer_code = NULL;
                END IF;
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
                 _payment_status,
                 IF(_update_date = '',  NOW(), _update_date),
                 _order_id,
                 (SELECT course_name FROM course WHERE course_id = _course_id),
                 (SELECT point_count FROM course WHERE course_id = _course_id),
                 @expired_date,
                 (SELECT paypal_item_number FROM course WHERE course_id = _course_id),
                 IF(_customer_code = '' OR customer_code IS NULL, _default_customer_code, _customer_code),
                 IF(_corporation_code = '' OR corporation_code IS NULL, _default_corporation_code, _corporation_code),
                 IF(_begin_date = '',  NOW(), DATE_FORMAT(_begin_date,'%Y-%m-%d %H:%i:%s')),
                 0
            );
        CASE
          WHEN _payment_way IN (0,1,9) THEN

            SET   _add_point = COALESCE((SELECT point_count FROM course WHERE course_id = _course_id),0);
            CASE  _add_point
              WHEN 0 THEN
                SET _rtn = 1;
              ELSE

                  -- Process with course free
                  -- CALL sp_disable_course_free(_student_id);

                  CALL lms_insert_project_course_student(_project_course_id, _project_id, _course_id, _student_id, _start_date, @expired_date, _start_date_origin);
            END CASE;
            -- UPDATE student
             -- SET
               -- course_id = IF(_set_course_id = 0, _course_id, _set_course_id)
               -- ,course_update_count = course_update_count + 1
             -- WHERE
               -- student_id = _student_id;

          ELSE
            SET _rtn = 1;

        END CASE;
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
        Schema::dropIfExists('sp_insert_point_subscription_lms');
    }
}
