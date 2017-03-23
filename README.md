Currency Conversions for Laravel 5.* 
=======================

[![Latest Stable Version](https://poser.pugx.org/msonowal/laraxchange/v/stable)](https://packagist.org/packages/msonowal/laraxchange)
[![Total Downloads](https://poser.pugx.org/msonowal/laraxchange/downloads)](https://packagist.org/packages/msonowal/laraxchange)
[![License](https://poser.pugx.org/msonowal/laraxchange/license)](https://packagist.org/packages/msonowal/laraxchange)

Laravel 5 Library for working with multi currency conversions currently using  http://fixer.io/ API.

Supports the entire Laravel 5.* releases.

In contrary to all other packages wherein it requires that you have to configure your provider, this library calls a free service and works with zero setup configs.
This package utilizes built in laravel cache driver for caching the conversion rates and it also provides commands for artisan lovers and which can be scheduled so that it will periodically updates your cache storage.
 
So you don't really have to worry about downloading/configuring API Keys.

It also provides a middleware which automatically determines the currency and set it for the Visitor if there are no currency set in the session which depends on GeoIp [package](https://packagist.org/packages/torann/geoip)

Just install the package, add the config and it is ready to use!

Requirements
============

* PHP >= 5.6.*
* Geoip package install it from [here](https://packagist.org/packages/torann/geoip) 

Installation
============

    composer require msonowal/laraxchange

Add the service provider and facade in your config/app.php

Service Provider

    Msonowal\Laraxchange\Providers\CurrencyServiceProvider::class,

Aliases (Facade)

    'Currency'      =>  Msonowal\Laraxchange\Facades\Currency::class,

Configuration
============

This library also supports optional configuration.

To get started, first publish the package config file:

```bash
php artisan vendor:publish --provider="Msonowal\Laraxchange\Providers\CurrencyServiceProvider"
```

- `base_currency`: defines the base currency for the app.
- `default_currency`: defines the default_currency when no currency code is present for conversions.
- `valid_currencies`: defines the currencies that are allowed to set for the applications.
- `cache_key`: defines the cache_key for storing and retrieval to use with caching.
- `cache_expiry`: defines the cache_expiry in minutes for how long the currencies will be store in cache.

It provides various built in helper methods to get the user currency or setting the currency 

Usage
=====


Set default currency of the visitor by using the middleware
add this below in `Kernal.php` in `app/http`

    'determine_currency'    =>  \Msonowal\Laraxchange\Middleware\SetDefaultUserCurrency::class,
    
Get ISO Currency_code of the visitor

    getUserCurrency();  // returns "USD"

Get ISO Currency_code of the visitor

    getUserCurrencySymbol();  // returns "$"

Get ISO Currency symbol of a currency

    getCurrencySymbol("USD");  // returns "$"

Convert value from base currency to another currency on the fly

    convertCurrency($value, "GBP");  // returns the value in GBP currency

Set base currency only for instance which can be changed on the fly

    Currency::setBaseCurrency("GBP"); // sets the base currency as specified for that instance when default base currency is different

Get All Conversions list of the currencies based on the base currency specified 

    Currency::getRates();  // returns list of values fore each currency

There are so many other methods which can be explored by yourself

Credits
=======

* Hakan Ensari for the awesome [fixer.io](http://fixer.io) web api
* Daniel Stainback for the GeoIp package
* MaxMind for the IP data



