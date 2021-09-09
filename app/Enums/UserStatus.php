<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserStatus extends Enum
{
    const ADMIN_ACCOUNT =   0;
    const GENERAL_USER_ACCOUNT =   1;
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::ADMIN_ACCOUNT:
                return '管理者';
                break;

            case self::GENERAL_USER_ACCOUNT:
                return '一般ユーザ';
                break;
        }
    }
}
