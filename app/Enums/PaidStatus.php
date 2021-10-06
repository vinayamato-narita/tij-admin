<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PaidStatus extends Enum implements LocalizedEnum
{
    const G = 0;
    const CSV = 1;
    const GCO未払 = 2;
    const GCO = 3;
    const GCO期限切 = 4;
}
