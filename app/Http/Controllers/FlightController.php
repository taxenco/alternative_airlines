<?php

namespace App\Http\Controllers;
use App\Services\AmadeusService;
use Illuminate\Http\Request;

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

