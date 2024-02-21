<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'symbol',
        'name',
    ];

    /**
     * Get the prices for the stock.
     */
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    /**
     * Get the report for the stock.
     */
    public function report()
    {
        // Current and previous prices
        $recentPrices = $this->prices()->orderBy('datetime', 'DESC')->take(2)->get();
        // If there are no prices, return an empty array
        if ($recentPrices->count() < 2) {
            return [];
        }

        // Current
        $currentPrice = $recentPrices->first();

        // Previous
        $previousPrice = $recentPrices->last();

        // Calculate the change
        return [
            'current' => $currentPrice,
            'previous' => $previousPrice,
            'change' => $currentPrice->close - $previousPrice->close,
            'change_percent' => ($currentPrice->close - $previousPrice->close) / $previousPrice->close * 100,
        ];
    }
}
