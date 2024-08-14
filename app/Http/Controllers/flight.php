<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AmadeusService;

class FlightController  extends Controller
{
    protected $amadeusService;

    // Inject the AmadeusService into the controller
    public function __construct(AmadeusService $amadeusService)
    {
        $this->amadeusService = $amadeusService;
    }

    // Example method to search for flights
    public function searchFlights(Request $request)
    {
        // Validate the request parameters (you can adjust the validation rules as needed)
        $validated = $request->validate([
            'departure_airport' => 'required|string|max:255',
            'arrival_airport' => 'required|string|max:255',
            'departure_date' => 'required|date|after_or_equal:today',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'passengers' => 'required|integer|min:1|max:10',
        ]);

        // Use the AmadeusService to search for flights
        try {
            $flights = $this->amadeusService->searchFlights($validated);
            return response()->json($flights);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch flights. Please try again later.'], 500);
        }
    }
    
}
