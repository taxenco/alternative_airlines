<?php

namespace App\Http\Controllers;

use App\Services\AmadeusService;
use Illuminate\Http\Request;
use App\Traits\DatesFormatTrait;

/**
 * Class FlightController
 * 
 * This controller handles flight search and airport search functionalities using the AmadeusService.
 */
class FlightController extends Controller
{
    use DatesFormatTrait;

    /**
     * @var AmadeusService
     */
    protected $amadeusService;

    /**
     * FlightController constructor.
     * 
     * @param AmadeusService $amadeusService
     */
    public function __construct(AmadeusService $amadeusService)
    {
        $this->amadeusService = $amadeusService;
    }

    /**
     * Search for flight offers based on the request parameters.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
            return response()->json(['error' => 'Unable to fetch flight offers. Please try again later.'], 500);
        }
    }
    
    /**
     * Handle the response from the flight search.
     * 
     * This method processes the raw flight offers data and formats it into a more
     * human-readable and usable format.
     * 
     * @param array $flightOffers
     * @return \Illuminate\Support\Collection
     */
    private function handleSearchFlightOffersResponse(array $flightOffers)
    {
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
