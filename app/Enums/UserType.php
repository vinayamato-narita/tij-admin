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
        'student_firstname' =>'姓',
        'student_lastname' => '名',
        'student_nickname' => 'ニックネーム',
        'student_email' => 'メールアドレス',
        'student_birthday' => '生年月日',
        'student_sex' => '性別(0:男性、1:女性、2:回答しない)',
        'company_name' => '法人名',
        'password' => 'パスワード',
        'lang_type'=>'言語（ja:日本語、en:英語、zh:中国語）'
        
    ];

    const COURSE_USER_KEY_IMPORT = [
        'course_id', 'email'
    ];

    const COURSE_STUDENT_KEY_IMPORT = [
        'student_firstname', 'student_lastname','student_nickname','student_email',
        'student_birthday','student_sex','company_name','password','lang_type'
    ];
}
