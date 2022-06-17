<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class PaymentWayEx extends Enum implements LocalizedEnum
{
    const CREDIT = 0;
    const PAYPAL = 1;
    const IMPORT = 2;
    const PENDING = 3;
}
