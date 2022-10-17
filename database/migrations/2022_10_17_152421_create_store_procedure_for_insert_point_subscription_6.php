<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProcedureForInsertPointSubscription6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_insert_point_subscription`;
        CREATE PROCEDURE `sp_insert_point_subscription`(IN _student_id INT,
        IN _course_id INT,
        IN _set_course_id INT,
        IN _amount VARCHAR(45),
        IN _tax VARCHAR(45),
        IN _currency_unit VARCHAR(45),
        IN _payment_way  VARCHAR(1),
        IN _start_date   VARCHAR(255),
        IN _payment_status VARCHAR(255),
        IN _first_name     VARCHAR(255),
        IN _last_name      VARCHAR(255),
        IN _order_id       VARCHAR(27),
        IN _customer_code VARCHAR (255),
        IN _corporation_code  VARCHAR(255),
        IN _payment_date VARCHAR(20),
        IN _expired_date VARCHAR(20))
        BEGIN
            DECLARE _rtn INT DEFAULT 0;
            DECLARE _add_point INT DEFAULT 0;

            SET @expired_date = IF(_expired_date = '',(SELECT DATE_ADD(IF(_start_date = '',  NOW(), DATE_FORMAT(_start_date,'%Y-%m-%d 23:59:59')), INTERVAL c.expire_day DAY) FROM course c WHERE c.course_id = _course_id), DATE_FORMAT(_expired_date,'%Y-%m-%d 23:59:59'));

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
                 IF(_payment_date = '',  NOW(), _payment_date),
                 _order_id,
                 (SELECT course_name FROM course WHERE course_id = _course_id),
                 (SELECT point_count FROM course WHERE course_id = _course_id),
                 @expired_date,
                 (SELECT paypal_item_number FROM course WHERE course_id = _course_id),
                 _customer_code,
                 _corporation_code
                 ,IF(_start_date = '',  NOW(), DATE_FORMAT(_start_date,'%Y-%m-%d %H:%i:%s'))
                 ,0
            );
        CASE
          WHEN _payment_way NOT IN (0,1,9) THEN
            SET _rtn = _rtn;
            -- SET   _add_point = COALESCE((SELECT point_count FROM course WHERE course_id = _course_id),0);
            -- CASE  _add_point
              -- WHEN 0 THEN
                -- SET _rtn = 1;
              -- ELSE
                  -- Process with course free
                  -- call sp_disable_course_free(_student_id);

            -- END CASE;
            -- UPDATE student
            --  SET
               -- course_id = IF(_set_course_id = 0, _course_id, _set_course_id)
               -- ,course_update_count = course_update_count + 1
             -- WHERE
               -- student_id = _student_id
            -- ;
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
        Schema::dropIfExists('sp_insert_point_subscription');
    }
}
