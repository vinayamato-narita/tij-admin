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
}
