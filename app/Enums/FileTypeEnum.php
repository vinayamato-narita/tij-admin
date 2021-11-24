<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class FileTypeEnum extends Enum
{
    const TEXT =   0;
    const PREPARATION_VIDEO =   1;
    const REVIEW_VIDEO = 2;
    const TEST_RELATED = 3;
}
