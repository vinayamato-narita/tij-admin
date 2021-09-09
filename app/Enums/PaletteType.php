<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PaletteType extends Enum
{
    const Transeeker = 1;
    const TranseekerSlim = 2;

    /**
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::Transeeker:
                return 'Transeeker用';
                break;

            case self::TranseekerSlim:
                return 'TranseekerSlim用';
                break;
            default:
                return "その他";
                break;
        }
    }
}
