<?php
namespace App\Services;
use Amadeus\Amadeus;

class AmadeusService
{
    protected $amadeus;

    public function __construct()
    {
        $clientId = env('AMADEUS_CLIENT_ID');
        $clientSecret = env('AMADEUS_CLIENT_SECRET');

        // Initialize the Amadeus client with your credentials
        $this->amadeus = Amadeus::builder($clientId, $clientSecret)->build();
    }

    public function searchFlights(array $params)
    {
        try {
            // Prepare the parameters for the flight search
            $searchParams = [
                'originLocationCode' => $params['departure_airport'],
                'destinationLocationCode' => $params['arrival_airport'],
                'departureDate' => $params['departure_date'],
                'adults' => $params['passengers'],
            ];

            // Add return date if provided
            if (!empty($params['return_date'])) {
                $searchParams['returnDate'] = $params['return_date'];
            }

            // Perform the search using the FlightOffersSearch service
            $response = $this->amadeus->getShopping()->getFlightOffers()->get($searchParams);

            // Return the flight data
            return $response;
        } catch (\Exception $e) {
            // Handle exceptions and return an error message
            return ['error' => $e->getMessage()];
        }
    }

    // Other Amadeus API methods can be added here
}
