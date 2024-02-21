<?php

namespace App\Http\Controllers;

// Models
use App\Models\Stock;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function __invoke()
    {
        $stocks = Stock::all();

        // Used pure JS in the view as It was only a one page
        return view('home', compact('stocks'));
    }
}
