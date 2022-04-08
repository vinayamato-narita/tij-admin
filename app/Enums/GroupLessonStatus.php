<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class GroupLessonStatus extends Enum
{
    const BEFORE_DECIDE =   0;
    const COURSE_DECIDE =   1;
    const CANCEL = 2;
}
