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
    SET @check_student_id_exist = (SELECT COUNT(student_id) AS ret FROM student WHERE student_id = _student_id AND is_lms_user = _is_lms_user );
    SET @check_mail_exist = (SELECT COUNT(student_id) AS ret FROM student WHERE student_email =  _student_email AND is_lms_user = _is_lms_user );
    SET @check_mail_and_id = (SELECT COUNT(student_id) AS ret FROM student WHERE student_id = _student_id AND student_email =  _student_email AND is_lms_user = _is_lms_user );
    SET @check_course_exist = (SELECT COUNT(course_id) FROM course WHERE course_id = _course_id AND is_set_course = 0 ) ;
    
    SELECT    
     @check_mail_exist,
     @check_mail_and_id,
     @check_course_exist,
     @check_student_id_exist
     ;
END";
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
