<?php

namespace App\Helpers;


class Helpers
{

    // GenHecCode will generate hexcode of length specified
    public static function GenHecCode(int $length): string
    {
        return bin2hex(random_bytes($length));
    }

    public static function runImageUpload()
    {
    }
}
