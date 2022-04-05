<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TestType extends Enum implements LocalizedEnum
{
    const CONFIRMED = 0;
    const ABILITY = 1;
    const ENDCOURSE = 2;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::CONFIRMED:
                return '確認テスト';
                break;

            case self::ABILITY:
                return '実力テスト';
                break;

            case self::ENDCOURSE:
                return 'コース修了テスト';
                break;

            default:
                return '';
        }
    }
}
