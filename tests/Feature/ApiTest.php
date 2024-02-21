<?php

use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;

beforeEach(function () {
    $this->seed(); // Assuming you have a Seeder class that populates the database with test data

});

// Test the stocks endpoint
it('returns the symbol and their name from stocks endpoint', function () {

    // Make a GET request to your API endpoint
    $response = $this->getJson('/api/stocks');

    // Assert that the response is successful
    $response->assertStatus(200);

    // Assert that the response has the expected structure
    $response->assertJsonStructure([
        'data' => [
            '*' => ['symbol', 'name']
        ]
    ]);
});


// Test the Prices endpoint
it('returns the history of stock prices from prices endpoint', function () {


    // Make a GET request to your API endpoint
    $response = $this->getJson('/api/price/IBM');

    // Assert that the response is successful
    $response->assertStatus(200);

    // Assert that the response has the expected structure
    $response->assertJsonStructure([
        'data' => [
            '*' => ['datetime', 'open', 'high', 'low', 'close', 'volume'],
        ]
    ]);
});

