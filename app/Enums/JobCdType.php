<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class JobCdType extends Enum implements LocalizedEnum
{
    const UNPROCESSED = "UNPROCESSED";
    const AUTHENTICATED = "AUTHENTICATED";
    const AUTH = "AUTH";
    const SALES = "SALES";
    const CAPTURE = "CAPTURE";
    const VOID = "VOID";
    const CANCEL = "CANCEL";
    const RETURN = "RETURN";
    const RETURNX = "RETURNX";
    const SAUTH = "SAUTH";
    const CHECK = "CHECK";
}
