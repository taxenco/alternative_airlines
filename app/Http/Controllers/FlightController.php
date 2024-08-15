<?php

namespace App\Http\Controllers;

use App\Services\AmadeusService;
use Illuminate\Http\Request;
use App\Traits\DatesFormatTrait;

/**
 * Class FlightController
 * 
 * This controller handles flight search and airport search functionalities using the AmadeusService.
 * It provides methods to search for flights and process the response from the Amadeus API.
 */
class FlightController extends Controller
{
    use DatesFormatTrait;

    /**
     * @var AmadeusService The service used to interact with the Amadeus API for flight searches.
     */
    protected $amadeusService;

    /**
     * FlightController constructor.
     * 
     * @param AmadeusService $amadeusService An instance of the AmadeusService to be used for flight searches.
     */
    public function __construct(AmadeusService $amadeusService)
    {
        $this->amadeusService = $amadeusService;
    }

    /**
     * Search for flight offers based on the request parameters.
     * 
     * This method validates the incoming request, performs a flight search using the AmadeusService,
     * and returns the search results as a JSON response. In case of errors, it returns a 500 response
     * with an error message.
     * 
     * @param Request $request The request instance containing search parameters.
     * @return \Illuminate\Http\JsonResponse A JSON response with flight offers or an error message.
     */
    public function index(Request $request)
    {
        // Validate the request parameters
        $validatedData = $request->validate([
            'originLocationCode' => 'required|string|max:255',
            'destinationLocationCode' => 'required|string|max:255',
            'departureDate' => 'required|date|after_or_equal:today',
            'returnRate' => 'nullable|date|after_or_equal:departureDate',
            'adults' => 'required|integer|min:1|max:10',
        ]);

        // Use the AmadeusService to search for flights
        try {
            $flightOffers = $this->amadeusService->searchFlights($validatedData);
            
            return response()->json(['data' => $this->handleSearchFlightOffersResponse($flightOffers)]);

        } catch (\Exception $e) {
            // Return an error response if there is an issue with the flight search
            return response()->json(['error' => 'Unable to fetch flight offers. Please try again later.'], 500);
        }
    }
    
    /**
     * Handle the response from the flight search.
     * 
     * This method processes the raw flight offers data and formats it into a more
     * human-readable and usable format.
     * 
     * @param array $flightOffers The raw flight offers data from the Amadeus API.
     * @return \Illuminate\Support\Collection A collection of formatted flight offers.
     */
    private function handleSearchFlightOffersResponse(array $flightOffers)
    {
        // Map through the flight offers to format the data
        return collect($flightOffers['data'])->map(function($offer) {
            $segments = $offer['itineraries'][0]['segments'];
            $segmentCount = count($segments);

            return [
                'id' => $offer['id'],
                'source' => $offer['source'],
                'itinerary' => [
                    'totalDuration' => $this->convertISO8601ToHumanReadable($offer['itineraries'][0]['duration']),
                    'stop' => $segmentCount > 1 ? $segmentCount - 1 : 0, // Number of stops
                    'segments' => collect($segments)->map(function($segment) {
                        return [
                            'departure' => [
                                'airportCode' => $segment['departure']['iataCode'],
                                'city' => $segment['departure']['iataCode'], // Use dictionary for city names if needed
                                'dateTime' => $this->formatDateTime($segment['departure']['at']),
                            ],
                            'arrival' => [
                                'airportCode' => $segment['arrival']['iataCode'],
                                'city' => $segment['arrival']['iataCode'], // Use dictionary for city names if needed
                                'dateTime' => $this->formatDateTime($segment['arrival']['at']),
                            ],
                            'carrier' => $segment['carrierCode'], // Use dictionary for carrier names if needed
                            'flightNumber' => $segment['number'],
                            'duration' => $this->convertISO8601ToHumanReadable($segment['duration']),
                        ];
                    })->toArray()
                ],
                'price' => [
                    'currency' => $offer['price']['currency'],
                    'total' => $offer['price']['total'],
                ]
            ];
        });
    }
}
