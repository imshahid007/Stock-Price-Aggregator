<?php

namespace App\Console\Commands;

use App\Models\Price;
// Models
use App\Models\Stock;
use App\Services\AlphaVantageService;
// Services
use Illuminate\Console\Command;

class FetchStockPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-stock-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch stock price from the API and store it in the database.';

    /**
     * Execute the console command.
     */
    public function handle(AlphaVantageService $service): void
    {
        // Get all the Stock
        Stock::all()->each(function (Stock $stock) use ($service) {
            //
            if (! empty($stock->symbol)) {
                $response = $service->intraday($stock->symbol, ['interval' => '5min']);
                // Fetch the stock price
                if (! is_null($response)) {
                    $response->each(function (Price $price) use ($stock) {
                        // Create or update the stock price
                        Price::updateOrCreate(
                            [
                                'stock_id' => $stock->id,
                                'datetime' => $price->datetime,
                            ],
                            [
                                'open' => $price->open,
                                'high' => $price->high,
                                'low' => $price->low,
                                'close' => $price->close,
                                'volume' => $price->volume,
                            ]
                        );
                    });

                    // Log the successful fetch
                    $this->info("Fetched stock price for {$stock->symbol}");
                }
            }
        });
    }
}
