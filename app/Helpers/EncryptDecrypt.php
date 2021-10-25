<?php

namespace App\Helpers;

use Hashids\Hashids;

class EncryptDecrypt
{
    public static function hashtoId($id)
    {
        $hashids = new Hashids('magicpay');
        return $hashids->encode($id);
    }

    public static function idtoHash($hash)
    {
        $hashids = new Hashids('magicpay');
        return $hashids->decode($hash)[0];
    }
}
