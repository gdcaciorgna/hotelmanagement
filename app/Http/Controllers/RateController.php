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
            $commodities = $request->input('commodities', []);
            $pivotData = [];

            foreach ($commodities as $commodityId) {
                $pivotData[$commodityId] = ['created_at' => now()];
            }

            $rate->commodities()->sync($pivotData);
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

        // 1️⃣ Obtener el precio anterior
        $previousPrice = $rate->currentPrice; 

        // 2️⃣ Actualizar la tarifa (excepto el precio)
        $rate->update($request->except('currentPrice'));

        // 3️⃣ Actualizar el precio y registrar en el historial si es diferente
        if ($request->input('currentPrice') != $previousPrice) {
            $rate->updateCurrentPrice($request->input('currentPrice')); 
        }

        // 4️⃣ Obtener comodidades actuales con su created_at
        $currentCommodities = $rate->commodities()->pluck('commodity_rate.created_at', 'commodities.id')->toArray();

        // 5️⃣ Obtener nuevas comodidades del request
        $newCommodities = $request->input('commodities', []);

        // 6️⃣ Construir el array de sync manteniendo created_at original si existe
        $pivotData = [];
        foreach ($newCommodities as $commodityId) {
            $pivotData[$commodityId] = ['created_at' => $currentCommodities[$commodityId] ?? now()];
        }

        // 7️⃣ Sincronizar comodidades sin modificar los created_at existentes
        $rate->commodities()->sync($pivotData); 
        
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
