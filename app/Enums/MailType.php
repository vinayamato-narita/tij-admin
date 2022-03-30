<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MailType extends Enum
{
    const FORGOTPASSWORD = 9;
    const CHANGEPASSSTUDENT = 6;
    const STUDENT_CANCEL_LESSON = 2;
    const TEACHER_CANCEL_LESSON = 16;
}
