<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class OptionUploadFile extends Enum implements LocalizedEnum
{
    const PC = 0;
    const CLOUD = 1;
}
