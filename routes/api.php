<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Flight;


Route::post('/search-flights', [Flight::class, 'index']);
