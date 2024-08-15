<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Services\AmadeusService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test class for the FlightController.
 *
 * This class contains test cases to validate the behavior of the FlightController,
 * including handling of valid data, validation errors, and service failures.
 */
class FlightControllerTest extends TestCase
{
    use WithFaker;

    /**
     * @var AmadeusService|\Mockery\MockInterface
     */
    protected $amadeusService;

    /**
     * Set up the test environment.
     *
     * Mocks the AmadeusService and binds it to the application container.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of the AmadeusService
        $this->amadeusService = Mockery::mock(AmadeusService::class);
        
        // Bind the mocked service to the application container
        $this->app->instance(AmadeusService::class, $this->amadeusService);
    }

    /**
     * Test the index method with valid data.
     *
     * Sends a POST request with valid flight search data and checks
     * if the response is correct and contains the expected JSON structure.
     *
     * @return void
     */
    public function testIndexWithValidData()
    {
        // Define the request data
        $data = [
            'originLocationCode' => 'LAX',
            'destinationLocationCode' => 'JFK',
            'departureDate' => now()->addDays(1)->format('Y-m-d'),
            'adults' => 1,
        ];

        // Define the mock response
        $mockResponse = [
            [
                "data" => [
                    [
                        "id" => "1",
                        "source" => "GDS",
                        "itinerary" => [
                            "totalDuration" => "2 hours 45 minutes",
                            "stop" => 0,
                            "segments" => [
                                [
                                    "departure" => [
                                        "airportCode" => "LAX",
                                        "city" => "Los Angeles",
                                        "dateTime" => "August 16, 2024, 8:00 AM"
                                    ],
                                    "arrival" => [
                                        "airportCode" => "JFK",
                                        "city" => "New York",
                                        "dateTime" => "August 16, 2024, 10:45 AM"
                                    ],
                                    "carrier" => "TP",
                                    "flightNumber" => "1351",
                                    "duration" => "2 hours 45 minutes"
                                ]
                            ]
                        ],
                        "price" => [
                            "currency" => "USD",
                            "total" => "101.28"
                        ]
                    ]
                ]
            ]
        ];
    
        // Set up the expectation for the searchFlights method
        $this->amadeusService->shouldReceive('searchFlights')
            ->once()
            ->with($data)
            ->andReturn($mockResponse);

        // Send the request and assert the response
        $response = $this->json('POST', route('flights.index'), $data);

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJson(['data' => $mockResponse]);
    }

    /**
     * Test the index method with a validation error.
     *
     * Sends a POST request with missing required data to trigger a validation error
     * and checks if the response indicates a validation failure.
     *
     * @return void
     */
    public function testIndexWithValidationError()
    {
        // Define the request data with missing 'originLocationCode'
        $data = [
            'destinationLocationCode' => 'JFK',
            'departureDate' => now()->addDays(1)->format('Y-m-d'),
            'adults' => 1,
        ];

        // Send the request and assert the validation error response
        $response = $this->json('POST', route('flights.index'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['originLocationCode']);
    }

    /**
     * Test the index method when the AmadeusService fails.
     *
     * Simulates a failure of the AmadeusService and checks if the controller handles
     * the failure gracefully, returning a proper error message.
     *
     * @return void
     */
    public function testIndexWhenAmadeusServiceFails()
    {
        // Define the request data
        $data = [
            'originLocationCode' => 'LAX',
            'destinationLocationCode' => 'JFK',
            'departureDate' => now()->addDays(1)->format('Y-m-d'),
            'returnDate' => now()->addDays(7)->format('Y-m-d'),
            'adults' => 1,
        ];

        // Set up the expectation for the searchFlights method to throw an exception
        $this->amadeusService->shouldReceive('searchFlights')
            ->once()
            ->with($data)
            ->andThrow(new \Exception('Service unavailable'));

        // Send the request and assert the error response
        $response = $this->json('POST', route('flights.index'), $data);

        $response->assertStatus(500)
            ->assertJson(['error' => 'Unable to fetch flight offers. Please try again later.']);
    }

    /**
     * Tear down the test environment.
     *
     * Closes the Mockery mock manager.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
