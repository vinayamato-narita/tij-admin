<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserType extends Enum
{
    const COURSE_USER_IMPORT_HEADER = [
        'course_id' => 'コースID',
        'email' => 'メールアドレス'
    ];

    const COURSE_STUDENT_IMPORT_HEADER = [
        'student_name' => '姓名',
        'student_nickname' => 'ニックネーム',
        'student_email' => 'メールアドレス',
        'student_birthday' => '生年月日',
        'student_sex' => '性別',
        'company_name' => '法人名',
        'password' => 'パスワード',
        
    ];

    const COURSE_USER_KEY_IMPORT = [
        'course_id', 'email'
    ];

    const COURSE_STUDENT_KEY_IMPORT = [
        'student_name', 'student_nickname','student_email',
        'student_birthday','student_sex','company_name','password'
    ];
}
