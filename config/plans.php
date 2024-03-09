<?php

return [
    'plans_url'      => '/planes', //if plan is required this is the route where the user will be redirected
    'openId'         => env('PLANS_OPEN_ID', null),
    'openApiKey'     => env('PLANS_OPEN_API_KEY', null),
    'openCountry'    => env('PLANS_OPEN_COUNTRY', null),
    'openProduction' => env('PLANS_OPEN_PRODUCTION', false),
];
