<?php

namespace App\Http\Controllers;
use App\Services\AmadeusService;
use Illuminate\Http\Request;
use Carbon\Carbon;
class FlightController extends Controller
{
    protected $amadeusService;

    public function __construct(AmadeusService $amadeusService)
    {
        $this->amadeusService = $amadeusService;
    }

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
    
            // Reduce the amount of information sent to the front-end
            $filteredOffers = collect($flightOffers['data'])->map(function($offer) {
                $segments = $offer['itineraries'][0]['segments'];
                $segmentCount = count($segments);

                return [
                    'id' => $offer['id'],
                    'source' => $offer['source'],
                    'itinerary' => [
                        'totalDuration' => $this->convertDurationToHumanReadable($offer['itineraries'][0]['duration']),
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
                                'duration' => $this->convertDurationToHumanReadable($segment['duration']),
                            ];
                        })->toArray()
                    ],
                    'price' => [
                        'currency' => $offer['price']['currency'],
                        'total' => $offer['price']['total'],
                    ]
                ];
            });

            return response()->json(['data' => $filteredOffers]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch flight offers. Please try again later.'], 500);
        }
    }
    

    /**
     * Convert ISO 8601 duration (e.g., PT3H50M) to a human-readable format.
     */
    private function convertDurationToHumanReadable($duration)
    {
        $hours = 0;
        $minutes = 0;

        preg_match('/PT(\d+H)?(\d+M)?/', $duration, $matches);

        if (isset($matches[1])) {
            $hours = (int) rtrim($matches[1], 'H');
        }

        if (isset($matches[2])) {
            $minutes = (int) rtrim($matches[2], 'M');
        }

        return ($hours > 0 ? $hours . ' hours ' : '') . ($minutes > 0 ? $minutes . ' minutes' : '');
    }

    /**
     * Format dateTime to a more human-readable format.
     */
    private function formatDateTime($dateTime)
    {
        return Carbon::parse($dateTime)->format('F j, Y, g:i A');
    }
    

    public function searchAirport(Request $request){
                // Validate the request parameters
                $validatedData = $request->validate([
                    'keyword' => 'required|string|max:255|min:3',
                ]);      
        // Use the AmadeusService to search for flights
        try {
            $airports = $this->amadeusService->searchForAirport($validatedData);
            return response()->json($airports);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch airports. Please try again later.'], 500);
        }
    }
    
}

