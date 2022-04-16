<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProcedureSpGetStudentTeacherInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS `sp_get_student_teacher_information`;
        CREATE PROCEDURE `sp_get_student_teacher_information`(IN search_teacher_id INT,
        IN search_student_id INT,
        IN _lang_type varchar(10))
        BEGIN

        SELECT
          COALESCE(ti.teacher_nickname,t.teacher_nickname) as teacher_nickname
          ,t.teacher_skypename
          ,t.teacher_sex
          ,COALESCE(ti.teacher_introduction, t.teacher_introduction) as teacher_introduction
          ,COALESCE(ti.teacher_university, t.teacher_university) as teacher_university
          ,COALESCE(ti.teacher_department, t.teacher_department) AS teacher_department
          ,t.teacher_hobby
          ,t.photo_savepath
          ,t.movie_savepath
          ,t.sound_savepath
          ,t.teacher_feature1
          ,t.teacher_feature2
          ,t.teacher_feature3
          ,t.teacher_feature4
          ,(SELECT COUNT(*)
         FROM teacher_bookmark WHERE student_id = search_student_id AND teacher_id = search_teacher_id) AS is_bookmarked
          ,COALESCE(ti.introduce_from_admin, t.introduce_from_admin) as introduce_from_admin
          FROM
          teacher t
          left join teacher_info ti on ti.teacher_id = t.teacher_id and ti.lang_type = _lang_type

        WHERE
          t.teacher_id = search_teacher_id
        AND EXISTS (SELECT * FROM teacher_lesson tl JOIN lesson l ON tl.lesson_id = l.lesson_id  WHERE tl.teacher_id = search_teacher_id)
        ;
        END;
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
        Schema::dropIfExists('sp_get_student_teacher_information');
    }
}
