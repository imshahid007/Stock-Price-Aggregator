<?php

use App\Models\Stock;
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

    // Assert that the fetched data matches the inserted data
    expect($fetchedStock->symbol)->toBe('IBM');
    expect($fetchedStock->name)->toBe('International Business Machines Corporation');

});
