<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class CommonService
{
  public function rand_string($length)
  {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwyz012345678";
    $str = "";
    $size = strlen($chars);
    for ($i = 0; $i < $length; $i++) {
      $str .= $chars[rand(0, $size - 1)];
    }
    return Hash::make($str);
  }
}
