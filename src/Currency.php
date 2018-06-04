<?php
namespace Msonowal\Laraxchange;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Currency
{
    private $client, $base_currency, $cache_key, $rates , $api_key;
    const DEFAULT_API_URL       =   'http://data.fixer.io/api/latest?';
    public $api_url;
    public $cache_expiry;

    public function __construct($base_currency = null)
    {   
        $this->client           =   new Client();
        $this->cache_expiry     =   config('currency.cache_expiry');
        $this->cache_key        =   config('currency.cache_key');
        $this->api_key        =   config('currency.api_key');
        $this->setBaseCurrency($base_currency);
        if (!$this->isRatesSameAsBaseCurrency()) {
            $this->updateRates();
        }
        //saveInputs
        //$this->setApiUrl();
    }
//    public function setApiUrl($url='default')
//    {
//        if ( $url=='default' ) {
//            $this->api_url          =   self::DEFAULT_API_URL;
//        }else{
//            $this->api_url          =   $url;
//        }
//    }
    public function getCacheKey()
    {
        return $this->cache_key.$this->base_currency;
    }
    public function getApiUrl()
    {
        return self::DEFAULT_API_URL.'&access_key='.$this->api_key.'&base='.$this->base_currency;
    }
    public function setBaseCurrency($currency_code = null)
    {
        $this->base_currency    =   strtoupper(is_null($currency_code) ? config('currency.base_currency') : $currency_code);
    }
    public function getBaseCurrency()
    {
        return $this->base_currency;
    }
    public function getLiveRates()
    {
        $res    =   $this->client->get($this->getApiUrl());
        $res    =   $res->getBody()->getContents();
        //TODO retry it
        return json_decode($res);
        //try catch and use fallback api if one is not responding
    }
    public function updateRates()
    {
        //cache([$this->getCacheKey() => $rates], $this->cache_expiry);
        $this->rates  =   Cache::remember($this->getCacheKey(), $this->cache_expiry, function () {
                            return $this->getLiveRates();
        });
    }
    public function setRates($rates)
    {
        $this->rates    =   $rates;
    }
    public function getRates()
    {
        if (is_null($this->rates)) {
            $this->rates  =   Cache::remember($this->getCacheKey(), $this->cache_expiry, function () {
                                return $this->getLiveRates();
            });
        }
        if (!$this->isRatesSameAsBaseCurrency()) {
            $this->updateRates();
        }
        //TODO code to updated the rates property via API for the base currency property and then return the rate by recursively calling this method;
        //throw new \Exception('Conversion rates are in different base currency expecting in '.$currency_code.' but got in '.$this->getRates()->base);
        return $this->rates;
    }
    public function lastUpdated()
    {
        return $this->getRates()->date;
    }
    public function isRatesSameAsBaseCurrency() : bool
    {
        if (is_null($this->rates)) {
            $this->updateRates();
            return  $this->isRatesSameAsBaseCurrency();
        }
        return $this->rates->base == $this->base_currency;
    }
    public function getConversionRate(string $currency_code)
    {
        $currency_code      =   strtoupper($currency_code);
        if ($this->getRates()->base == $currency_code) {
            return 1;
        }
        return $this->getRates()->rates->{$currency_code};
        //dd(property_exists($res->rates, 'INR'), $res->rates->{'INR'}, cache('currency.rates'));
    }
    /**
     * Convert the value to the the specified $currency_code from base currency which is USD by default.
     * @param float $value
     * @param string $currency_code
     * @param bool $ignore_decimals This will ignore the decimal parts and append the original decimals
     * @param int $round if decimals are not ignored then it will round up the value to specified by default is 2
     * @return float if
     */
    public function convertRate($value, string $currency_code, bool $ignore_decimals = true, $round = 2)
    {
        if ($ignore_decimals) {
            $original   =   $value;
            $value      =   floor($value);
            $original   =   $original - $value; //saving the decimal part from the actual value
            $value      =   floor($this->getConversionRate($currency_code) * $value);
            $value      +=  $original;
            //dd(get_defined_vars(), $this);
            return $value;
        }
        return round(($this->getConversionRate($currency_code) * $value), $round);
    }
}
