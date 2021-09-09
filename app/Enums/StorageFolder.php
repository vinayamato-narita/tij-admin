<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StorageFolder extends Enum
{
    const FILE_FIRMWARE = "firmware";
    const IMPORT = "import";
}
