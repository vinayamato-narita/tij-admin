<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StudentEntryType extends Enum implements LocalizedEnum
{
    const USING = 0;
    const TEMPORARYREGISTRATION = 1;
    const INVALID = 2;
    const CANCEL = 3;
    const WAITINGCANCEL = 5;
}
