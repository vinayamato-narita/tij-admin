<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeacherForgotPassMigration3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $pc1 = "CREATE  PROCEDURE `sp_check_teacher_changepass`(IN _teacher_email varchar(255))
BEGIN
	SELECT
		teacher_id,
		remember_token as is_sercurity
	FROM    teacher
	WHERE
		md5(teacher_email) = _teacher_email;
END";
        \Illuminate\Support\Facades\DB::unprepared($pc1);

        $pc2 = "CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_chgpass_teacher`(IN _teacher_email varchar(255),
	IN _teacher_password varchar(255))
BEGIN
  UPDATE teacher
  SET
	password = _teacher_password,
	remember_token = NULL
	WHERE
		md5(teacher_email) = _teacher_email;
END";
        \Illuminate\Support\Facades\DB::unprepared($pc2);
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
