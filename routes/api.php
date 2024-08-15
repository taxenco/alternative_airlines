<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;

/**
 * Define routes for the application.
 *
 * Registers the route for searching flights. 
 * The route responds to POST requests at '/search-flights' 
 * and uses the `index` method of the `FlightController` 
 * to handle the request.
 *
 * @return void
 */
Route::post('/search-flights', [FlightController::class, 'index'])->name('flights.index');
