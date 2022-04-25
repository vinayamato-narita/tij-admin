<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LessonScheduleRole extends Enum
{
    const LESSON_SCHEDULE_STATUS_IN_PRESENT = -1;
    const LESSON_SCHEDULE_STATUS_IN_PAST = -2;
}
