<?php

namespace App\Http\Controllers;
use App\Models\Commodity;

use Illuminate\Http\Request;

class CommodityController extends Controller
{
    public function index(){

        $user = auth()->user();
    
        // Consulta base para obtener las limpiezas activas
        $commoditiesQuery = Commodity::query();
    
        // Aplicar filtro de usuario si es de tipo "Guest"
        if ($user->userType === 'Guest') {
            $commoditiesQuery->where('user_id', $user->id);
        }
    
        // Paginación y obtención de resultados finales
        $commodities = $commoditiesQuery->simplePaginate(30);

        return view('commodities.index')->with('commodities', $commodities);
    }

    public function create() {
        return view('commodities.commodityInfo', ['action' => 'create']);
    }

    public function store(Request $request) {
        $rules = [
            'title' => 'required|max:250',
            'description' => 'required|max:1000'
        ];

        $request->validate($rules);
        $commodity = Commodity::create($request->except('currentPrice'));
        $commodity->updateCurrentPrice($request->input('currentPrice'));
        return redirect()->route('commodities.index');
    }

    public function edit($id) {
        $commodity = Commodity::findOrFail($id);
        return view('commodities.commodityInfo', ['commodity' => $commodity, 'action' => 'edit']);
    }

    public function update($id, Request $request){

        $request->validate([
            'title' => 'required|max:250',
            'description' => 'required|max:1000',
            'currentPrice' => 'required|numeric'

        ]);

        $commodity = Commodity::findOrFail($id);
        $commodity->update($request->except('currentPrice'));
        $commodity->updateCurrentPrice($request->input('currentPrice'));

        return redirect()->route('commodities.index');
    }
    
    public function destroy(Request $request){
        $commodity = Commodity::findOrFail($request->id);

        $hasBookings = $commodity->bookings()->exists();
        $hasRates = $commodity->rates()->exists();

        if ($hasBookings || $hasRates) {
            return redirect()->route('commodities.edit', $commodity->id)
                ->withErrors('No se puede eliminar la comodidad porque está asociada a reservas o tarifas.');
        }        
        $commodity->delete();
        return to_route('commodities.index')->with('success', 'Comodidad eliminada exitosamente.');
    }

    public function commoditiesReport(){
        $commodities = Commodity::withCount('bookings')->orderBy('bookings_count', 'desc')->get();
        return view('reports.commodities')->with('commodities', $commodities);
    }

    public function show($id) {
        $commodity = Commodity::with('bookings.user')->findOrFail($id);
        $response =  response()->json([
            'id' => $commodity->id,
            'title' => $commodity->title,
            'description' => $commodity->description,
            'bookings' => $commodity->bookings,
        ]);
        return $response;
    }    

}
