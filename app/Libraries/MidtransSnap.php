<?php

namespace App\Libraries;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransSnap
{
    public function __construct()
    {
        Config::$serverKey = 'SB-Mid-server-zduJyjlt2qozBS5zE8flBk06';
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken($params)
    {
        return Snap::createTransaction($params)->token;
    }
}