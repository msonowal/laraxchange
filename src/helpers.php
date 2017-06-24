<?php
function getBaseCurrency()
{
    return strtoupper(config('currency.base_currency'));
}
function getDefaultCurrency()
{
    return strtoupper(config('currency.default_currency'));
}
function getAvailableCurrencies() : array
{
    return array_keys(config('currency.valid_currencies'));
}
function isCurrencySupported(string $currency_code)
{
    $currency_code      =   strtoupper($currency_code);
    $currencies         =   getAvailableCurrencies();
    return in_array($currency_code, $currencies);
}
function setUserCurrency($currency_code)
{
    $currency_code  =   strtoupper($currency_code);
    if (!isCurrencySupported($currency_code)) {
        $currency_code   =   getDefaultCurrency();
    }
    $key            =   config('currency.session_currency_key');
    session([$key => $currency_code]);
}
function getUserCurrency()
{
    $key    =   config('currency.session_currency_key');
    return session($key);
}
function getUserCurrencySymbol()
{
    return getCurrencySymbol(getUserCurrency());
}
function getUserCurrencyValue($value, $ignore_decimals = true)
{
    $currency_code  =   getUserCurrency();
    return \Msonowal\Laraxchange\Facades\Currency::convertRate($value, $currency_code, $ignore_decimals);
}
function convertCurrency($value, $currency_code, $ignore_decimals = true)
{
    //this will convert base currency to specified currency
    return \Msonowal\Laraxchange\Facades\Currency::convertRate($value, $currency_code, $ignore_decimals);
}
//TODO get user price value by determining from session
function setDefaultCurrencyIfNotSet($currency)
{
    if (is_null(getUserCurrency())) {
        setUserCurrency($currency);
    }
}
function getCurrencySymbol($currency_code)
{
    $currencies     =   config('currency.valid_currencies');
    return $currencies[strtoupper($currency_code)];
}
