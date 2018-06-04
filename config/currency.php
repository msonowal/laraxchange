<?php

return [
    'base_currency'         =>  env('BASE_CURRENCY', 'USD'),
    'default_currency'      =>  env('DEFAULT_CURRENCY', 'USD'),
    'session_currency_key'  =>  env('SESSION_CURRENCY_KEY', 'user_currency'),

    //define the symbols for the currencies you want to display for valid currencies
    'valid_currencies'      =>  [
        'USD'   =>  '$',
        'EUR'   =>  '€',
        'GBP'   =>  '£',
    ],

    'cache_key'     =>  'currency_conversions_list',
    'cache_expiry'  =>  env('CURRENCY_CACHE', 60*72), //in minutes default set to 48 hour
    'api_key'   =>  env('CURRENCY_API_KEY'),
];
