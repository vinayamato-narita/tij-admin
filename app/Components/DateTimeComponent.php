<?php
namespace App\Components;

class DateTimeComponent
{
    public static function getDate($str)
    {
        $result = "";
        if ($str != "") {
            $result = date('Y-m-d', strtotime($str));
        }
        return $result;
    }

    public static function getStartEndTime($str1, $str2)
    {
        $result = "";
        if ($str1 != "" && $str2 != "" ) {
            $result = date('H:i', strtotime($str1)) . "~" . date('H:i', strtotime($str2));
        }
        return $result;
    }
}
