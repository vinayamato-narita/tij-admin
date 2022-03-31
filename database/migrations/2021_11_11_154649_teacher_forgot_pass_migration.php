<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeacherForgotPassMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $pc1 = "CREATE  PROCEDURE `sp_check_forgot_teacher`(IN _teacher_email varchar(255),
	IN _lang_type VARCHAR(10))
BEGIN
	SELECT
	teacher.teacher_id,
	COALESCE(ti.teacher_name ,teacher.teacher_name) AS teacher_name,
	teacher.teacher_email,
	teacher.teacher_birthday
	FROM teacher
	LEFT JOIN teacher_info AS ti ON ti.teacher_id = teacher.teacher_id AND ti.lang_type = _lang_type
	WHERE
		teacher.teacher_email = _teacher_email;
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
