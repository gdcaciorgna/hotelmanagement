<?php

namespace App\Http\Controllers;
use App\Models\Rate;
use App\Models\Commodity;
use App\Models\Booking;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function index(){
        $rates = Rate::query()
        ->simplePaginate(30);
        
        return view('rates.index')->with('rates', $rates);
    }

    public function create() {
        $commodities = Commodity::all();
        return view('rates.rateInfo', ['action' => 'create', 'commodities' => $commodities]);
    }

    public function store(Request $request) {
        $rules = [
            'title' => 'required|max:250|unique:rates,title',
            'description' => 'required|max:1000',
            'commodities' => 'array',
            'currentPrice' => 'required|numeric|min:0' 

        ];

        $request->validate($rules);
        $rate = Rate::create($request->except('currentPrice'));
        $rate->updateCurrentPrice($request->input('currentPrice'));
        if ($request->has('commodities')) {
            $rate->commodities()->sync($request->input('commodities'));
        }    
        return redirect()->route('rates.index')->with('success', 'Tarifa creada exitosamente.');
    }

    public function edit($id) {
        $rate = Rate::findOrFail($id);
        $commodities = Commodity::all();
        return view('rates.rateInfo', ['rate' => $rate, 'action' => 'edit', 'commodities' => $commodities]);
    }

    public function update($id, Request $request){
        $request->validate([
            'title' => 'required|max:250|unique:rates,title,' . $id,
            'description' => 'required|max:1000',
            'commodities' => 'array',
            'currentPrice' => 'required|numeric|min:0' 

        ]);

        $rate = Rate::findOrFail($id);
        $rate->update($request->except('currentPrice'));
        $rate->updateCurrentPrice($request->input('currentPrice'));
        $rate->commodities()->sync($request->input('commodities', [])); 
        return redirect()->route('rates.index')->with('success', 'Tarifa modificada exitosamente.');
    }
    
    public function destroy(Request $request)
    {
        $rate = Rate::findOrFail($request->id);
    
        // Comprobar si hay reservas asociadas a esta tarifa
        $hasBookings = Booking::where('rate_id', $rate->id)->exists();
    
        if ($hasBookings) {
            return redirect()->route('rates.edit', $request->id)
                ->withErrors('No se puede eliminar la tarifa porque hay reservas asociadas a ella.');
        }
    
        $rate->delete();
        return to_route('rates.index')->with('success', 'Tarifa eliminada exitosamente.');
    }

}
