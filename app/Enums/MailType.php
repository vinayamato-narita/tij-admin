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
    const STUDENT_CANCEL_LESSON = 50;
    const TEACHER_CANCEL_LESSON = 16;
    const MAIL_STUDENT_BEFORE_LESSON_START = 1;
    const MAIL_TEACHER_BEFORE_LESSON_START = 17;
    const OPEN_GROUP_LESSON = 100;
}
