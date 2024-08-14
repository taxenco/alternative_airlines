<?php


namespace App\Services;

use Amadeus\Amadeus;

class AmadeusService
{
    protected $amadeus;

    public function __construct()
    {
        // Initialize the Amadeus client here
        $this->amadeus = Amadeus::builder()
            ->setClientId(config('services.amadeus.client_id'))
            ->setClientSecret(config('services.amadeus.client_secret'))
            ->build();
    }

    public function searchFlights($params)
    {
        return $this->amadeus->get('/v2/shopping/flight-offers', $params)->getData();
    }

    // Other Amadeus API methods can be added here
}
