<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeacherForgotPassMigration2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $pc1 = "CREATE  PROCEDURE `sp_set_sercurity_teacher`(IN _teacher_email varchar(255),
	IN _is_sercurity varchar(255))
BEGIN
  UPDATE teacher
  SET
		remember_token = _is_sercurity
		
  WHERE
    teacher_email = _teacher_email;
END
";
        \Illuminate\Support\Facades\DB::unprepared($pc1);
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
