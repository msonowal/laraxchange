<?php

namespace Msonowal\Laraxchange\Middleware;

use Closure;

class SetDefaultUserCurrency
{
    public function handle($request, Closure $next)
    {
        $location   =   geoip()->getLocation();
        setDefaultCurrencyIfNotSet($location->currency);
        return $next($request);
    }
}
