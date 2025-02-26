<?php

namespace App\Helpers;

use Hashids\Hashids;

class HashidHelper
{
    private static function getInstance()
    {
        return new Hashids(config('hashids.salt'), config('hashids.length'));
    }

    public static function encrypt($id)
    {
        return self::getInstance()->encode($id);
    }

    public static function decrypt($hash)
    {
        $decoded = self::getInstance()->decode($hash);
        return !empty($decoded) ? $decoded[0] : null;
    }
}
