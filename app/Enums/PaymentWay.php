<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaymentWay extends Enum
{
    const CREDIT = 0;
    const PAYPAL = 1;
    const IMPORT = 2;
}
