<?php

return [
    'base_url' => env('AMADEUS_BASE_URL', 'https://test.api.amadeus.com'),
    'endpoints' => [
        'token' => '/v1/security/oauth2/token',
        'flight_offers' => '/v2/shopping/flight-offers',
    ],
];