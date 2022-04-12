<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class AdminRole extends Enum implements LocalizedEnum
{
    const SYSTEM = 1;
    const BUSINESS = 2;
}
