<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserType extends Enum
{
    const COURSE_USER_IMPORT_HEADER = [
        'course_id' => 'コースID',
        'email' => 'メールアドレス'
    ];

    const COURSE_USER_KEY_IMPORT = [
        'course_id', 'email'
    ];
}
