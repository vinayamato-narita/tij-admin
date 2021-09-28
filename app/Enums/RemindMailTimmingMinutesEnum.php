<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RemindMailTimmingMinutesEnum extends Enum
{
    const OptionOne = 30;
    const OptionTwo = 60;
    const OptionThree = 90;
    const OptionFour = 120;
}
