<?php

namespace Msonowal\Laraxchange\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class SetDefaultUserCurrency
{
    public function handle($request, Closure $next)
    {
        try {
            //$location   =   geoip()->getLocation(); //Commented cause it was giving inconsistent results
            $location   =   geoip($request->ip());
            $currency   =   $location->currency;
            Log::debug('Detected Location ', $location->toArray());
        } catch (\Exception $ex) {
            Log::debug('Exception caught '. $ex->getMessage());
        }
        if (is_null($currency) || $currency=='') {
            $currency = config('currency.default_currency');
        }
        Log::debug('Setting Currency if not Set previously :'. $currency);
        setDefaultCurrencyIfNotSet($currency);
        Log::debug('Loading currency of Session IP :'.$request->ip(). ' Currency: '. $currency);
        return $next($request);
    }
}
