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
            return response()->json($flightOffers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch flight offers. Please try again later.'], 500);
        }
    }
    
}

