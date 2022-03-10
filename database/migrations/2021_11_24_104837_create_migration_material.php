<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMigrationMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure1 = "CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_student_lesson_text_list_html`(
            IN _lesson_name varchar(255),
            IN _lesson_text_name varchar(255),
            IN _teacher_id INT
        )
        BEGIN
         SELECT
           lss.lesson_name,
           lss.lesson_id,
           ltx.lesson_text_id,
           ltx.lesson_text_name,
           ltx.lesson_text_description,
           ltx.lesson_text_url,
           ltx.lesson_text_url_for_teacher,
           ltx.lesson_text_no
         FROM
           lesson_text ltx
           LEFT JOIN lesson_text_lesson ltl ON ltl.lesson_text_id = ltx.lesson_text_id
           LEFT JOIN lesson lss ON lss.lesson_id = ltl.lesson_id
           LEFT JOIN teacher_lesson tl on tl.lesson_id = lss.lesson_id
         WHERE
           lss.lesson_name LIKE CONCAT('%',_lesson_name,'%')
           AND ltx.lesson_text_name LIKE CONCAT('%',_lesson_text_name,'%')
           AND tl.teacher_id = _teacher_id
           ORDER BY lss.display_order, lss.lesson_name, ltx.lesson_text_no, ltx.lesson_text_name;
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
        Schema::dropIfExists('migration_material');
    }
}
