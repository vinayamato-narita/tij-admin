<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class PaymentWay extends Enum implements LocalizedEnum
{
    const CREDIT = 0;
    const PAYPAL = 1;
    const IMPORT = 2;
}
