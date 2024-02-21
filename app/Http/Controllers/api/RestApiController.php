<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PriceResource;
// use the model
use App\Http\Resources\StockResource;
// Resource
use App\Models\Stock;
use Illuminate\Support\Facades\Cache;

class RestApiController extends Controller
{
    /**
     * Display a listing of the stock resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stocks()
    {
        return StockResource::collection(Stock::paginate(10));
    }

    /* Price History for a specific stock */
    public function stockPriceHistory(Stock $stock)
    {
        return PriceResource::collection($stock->prices()->orderBy('datetime', 'DESC')->paginate(10));
    }

    /* report for a specific stock */
    public function stockReport(Stock $stock)
    {
        // Cache TTL
        $ttl = config('alphavantage.cache_duration');

        // Get the report
        $report = $stock->report();
        // If there is no report, return a 404
        if (empty($report)) {
            return response()->json(['message' => 'No data available'], 404);
        }
        // Cache the result
        $report = Cache::remember('report-'.$stock->symbol, $ttl, function () use ($report) {
            return $report;
        });
        // report
        $report['symbol'] = $stock->symbol;
        $report['current'] = new PriceResource($report['current']);
        $report['previous'] = new PriceResource($report['previous']);
        $report['change'] = number_format($report['change'], 2, '.', '');
        $report['change_percent'] = number_format($report['change_percent'], 2, '.', '');

        //
        return response()->json($report);
    }
}
