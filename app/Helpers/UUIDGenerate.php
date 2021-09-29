<?php

namespace App\Helpers;

use App\Models\Wallet;

class UUIDGenerate
{
    public static function account_number()
    {
        $number = mt_rand(1000000000000000, 9999999999999999);

        if (Wallet::where('account_number', $number)->exists()) {
            self::account_number();
        }

        return $number;
    }
}
