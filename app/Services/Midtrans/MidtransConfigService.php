<?php

namespace App\Services\Midtrans;

use App\Models\MidtransSetting;
use Midtrans\Config as MidtransConfig;

class MidtransConfigService
{
    /**
     * Hydrate Midtrans PHP SDK config from persisted settings.
     */
    public function configure(): void
    {
        $setting = MidtransSetting::first();

        if (! $setting || ! $setting->server_key) {
            throw new \RuntimeException('Midtrans server key is not configured.');
        }

        MidtransConfig::$serverKey = $setting->server_key;
        MidtransConfig::$isProduction = (bool) $setting->is_production;
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;
    }
}
