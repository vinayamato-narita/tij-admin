<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AutoRecording extends Enum
{
    const LOCAL =   0;
    const CLOUD =   1;
    const NONE = 2;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::LOCAL:
                return 'local';
                break;

            case self::CLOUD:
                return 'cloud';
                break;

            case self::NONE:
                return 'none';
                break;
        }
    }
}
