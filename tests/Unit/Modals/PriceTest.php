<?php

use App\Models\Stock;
use App\Models\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can insert and fetch data from the Stock model', function () {
    // Insert data into the Stock model
    $stock = Stock::create([
        'symbol' => 'IBM',
        'name' => 'International Business Machines Corporation',
        'created_at' => now(),
    ]);

    // Fetch the inserted data from the Stock model
    $fetchedStock = Stock::find($stock->id);

    // Create a price for the stock
    $price = Price::create([
        'stock_id' => $stock->id,
        'datetime' => now(),
        'open' => 100.00,
        'high' => 101.00,
        'low' => 99.00,
        'close' => 100.50,
        'volume' => 1000000,
    ]);

    // Fetch the inserted data from the Price model
    $fetchedPrice = Price::find($price->id);

    // Assert that the fetched data matches the inserted data
    expect($fetchedStock->symbol)->toBe('IBM');
    expect($fetchedPrice->stock_id)->toBe($stock->id);

});
