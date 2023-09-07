<?php


namespace App\Enums;


use BenSampo\Enum\Enum;

final class TeacherShowFlag extends Enum
{
    const HIDE = 0;
    const SHOW = 1;
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::HIDE:
                return '無効';
                break;

            case self::SHOW:
                return '有効';
                break;
        }
    }


}
