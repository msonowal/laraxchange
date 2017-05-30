<?php

namespace Msonowal\Laraxchange\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class SetDefaultUserCurrency
{
    public function handle($request, Closure $next)
    {
        $location   =   geoip()->getLocation();
        Log::info($location->toArray());
        $currency   =   $location->currency;

        if ( is_null($currency) || $currency=='' ) {
            $currency = config('currency.default_currency');
        }
        setDefaultCurrencyIfNotSet($currency);
        Log::info($currency);
        return $next($request);
    }
}
