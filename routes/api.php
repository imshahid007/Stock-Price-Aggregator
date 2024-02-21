<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Get the list of all stocks (E.g IBM, AAPL, GOOGL)
Route::get('/stocks', [App\Http\Controllers\api\RestApiController::class, 'stocks']);

// Get the pricing data of a specific symbol (E.g IBM)
Route::get('/price/{stock:symbol}', [App\Http\Controllers\api\RestApiController::class, 'stockPriceHistory']);

// Get the report for a specific stock (E.g IBM)
Route::get('/report/{stock:symbol}', [App\Http\Controllers\api\RestApiController::class, 'stockReport']);
