<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class AmadeusService
{
    /**
     * @var string $accessToken Amadeus API access token.
     */
    protected string $accessToken;

    /**
     * Constructor to initialize the AmadeusService.
     * Retrieves the Amadeus access token upon instantiation.
     *
     * @throws \Exception If the access token could not be retrieved.
     */
    public function __construct()
    {
        $this->accessToken = $this->getAmadeusAccessToken();
    }

    /**
     * Retrieve the Amadeus access token using client credentials.
     *
     * @return string Access token for Amadeus API.
     *
     * @throws \Exception If the access token could not be retrieved.
     */
    private function getAmadeusAccessToken(): string
    {
        $baseUrl = config('amadeus.base_url');
        $tokenEndpoint = config('amadeus.endpoints.token');
        $url = $baseUrl . $tokenEndpoint;

        $clientSecret = env('AMADEUS_CLIENT_SECRET');
        $clientId = env('AMADEUS_CLIENT_ID');

        // Make an HTTP POST request to retrieve the access token
        $response = Http::asForm()->post($url, [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        // Check if the request was successful and return the token
        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        // Throw an exception if the token retrieval failed
        throw new \Exception('Failed to retrieve Amadeus access token');
    }

    /**
     * Search for flights using the Amadeus API.
     *
     * @param array $params Query parameters for searching flights.
     *
     * @return array Flight offers data returned by the API.
     *
     * @throws \Exception If the flight offers could not be retrieved.
     */
    public function searchFlights(array $params): array
    {
        // Ensure that the access token is set
        if (empty($this->accessToken)) {
            throw new \Exception('Access token is missing.');
        }

        $baseUrl = config('amadeus.base_url');
        $flightOffersEndpoint = config('amadeus.endpoints.flight_offers');

        // Build the full URL with query parameters appended
        $url = $baseUrl . $flightOffersEndpoint . '?' . http_build_query($params);

        // Set the Authorization header with the Bearer token
        $headers = [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ];

        // Make an HTTP GET request with the Authorization header
        $response = Http::withHeaders($headers)->get($url);

        // Check if the request was successful and return the flight offers data
        if ($response->successful()) {
            return $response->json();
        }

        // Throw an exception if the flight offers retrieval failed
        throw new \Exception('Failed to retrieve flight offers: ' . $response->body());
    }


    // Additional Amadeus API methods can be added here
}
