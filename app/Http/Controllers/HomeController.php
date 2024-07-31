<?php

namespace App\Http\Controllers;
use App\Models\Commodity;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $commodities = Commodity::query()
        ->simplePaginate(30);
        return view('home')->with('commodities', $commodities);
    }
}
