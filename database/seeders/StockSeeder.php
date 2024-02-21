<?php

namespace Database\Seeders;

use App\Models\Stock;
// Models
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        try {
            $stocks = $this->predefined();
            // Create the stocks
            if (! empty($stocks) && is_array($stocks)) {
                //
                Stock::insert($stocks);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * The stocks to be inserted into the database.
     */
    private function predefined()
    {
        return [
            ['symbol' => 'IBM', 'name' => 'International Business Machines Corporation', 'created_at' => now(), 'updated_at' => now()],
            ['symbol' => 'AAPL', 'name' => 'Apple Inc.', 'created_at' => now(), 'updated_at' => now()],
            ['symbol' => 'GOOGL', 'name' => 'Alphabet Inc.', 'created_at' => now(), 'updated_at' => now()],
            ['symbol' => 'MSFT', 'name' => 'Microsoft Corporation', 'created_at' => now(), 'updated_at' => now()],
            ['symbol' => 'AMZN', 'name' => 'Amazon.com Inc.', 'created_at' => now(), 'updated_at' => now()],
            ['symbol' => 'META', 'name' => 'Meta Platforms Inc.', 'created_at' => now(), 'updated_at' => now()],
            ['symbol' => 'TSLA', 'name' => 'Tesla Inc.', 'created_at' => now(), 'updated_at' => now()],
            ['symbol' => 'NVDA', 'name' => 'NVIDIA Corporation', 'created_at' => now(), 'updated_at' => now()],
            ['symbol' => 'PYPL', 'name' => 'PayPal Holdings Inc.', 'created_at' => now(), 'updated_at' => now()],
            ['symbol' => 'ADBE', 'name' => 'Adobe Inc.', 'created_at' => now(), 'updated_at' => now()],

            // Add more stocks as needed
        ];
    }
}
