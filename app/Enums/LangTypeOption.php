<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class LangTypeOption extends Enum implements LocalizedEnum
{
    const JAPANESE ='ja';
    const ENGLISH = 'en';
    const CHINESE = 'zh';
}
