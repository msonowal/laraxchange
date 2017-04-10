<?php

namespace Msonowal\Laraxchange\Middleware;

use Closure;

class SetDefaultUserCurrency
{
    public function handle($request, Closure $next)
    {
        $location   =   geoip()->getLocation();
        $currency   =   $location->currency;

        if ( is_null($currency) || $currency=='' ) {
            $currency = config('currency.default_currency');
        }
        setDefaultCurrencyIfNotSet($currency);
        return $next($request);
    }
}
