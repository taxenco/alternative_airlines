<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController ;


Route::post('/search-flights', [FlightController ::class, 'index']);
