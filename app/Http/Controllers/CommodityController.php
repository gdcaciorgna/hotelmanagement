<?php

namespace App\Http\Controllers;
use App\Models\Commodity;

use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'description' => 'required|max:1000',
            'currentPrice' => 'required|numeric|min:0' 
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
            'currentPrice' => 'required|numeric|min:0' 
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

    public function commoditiesReport(Request $request) {
        // Obtener los valores de las fechas desde la solicitud
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;

        // Si las fechas se proporcionan, realiza las validaciones
        if ($startDate && $endDate) {
            $currentDate = Carbon::today(); // Obtiene la fecha actual sin la hora

            // Verificar que las fechas no sean mayores que la fecha actual
            if ($startDate->gt($currentDate) || $endDate->gt($currentDate)) {
                return redirect()->back()->withErrors(['startDate' => 'La fecha desde y la fecha hasta deben ser anteriores a la fecha actual.'])->withInput();
            }

            // Verificar que la fecha hasta sea igual o posterior a la fecha desde
            if ($endDate->lt($startDate)) {
                return redirect()->back()->withErrors(['endDate' => 'La fecha hasta debe ser igual o posterior a la fecha desde.'])->withInput();
            }
        }

        // Construir la consulta de comodidades
        $commoditiesQuery = Commodity::withCount(['bookings as bookings_count' => function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                // Filtrar por rango de fechas en la tabla intermedia
                $query->whereBetween('booking_commodity.created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
            }
        }])
        ->with(['bookings' => function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                // Traer detalles de las reservas en el rango de fechas
                $query->whereBetween('booking_commodity.created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                    ->select('bookings.id', 'booking_commodity.created_at'); // Seleccionar columnas necesarias
            }
        }])
        ->having('bookings_count', '>', 0) // Filtrar por comodidades con al menos una reserva
        ->orderBy('bookings_count', 'desc') // Ordenar por cantidad de reservas
        ->orderBy('title', 'asc'); // Ordenar por titulo de la comodidad alfabeticamente

        // Obtener las comodidades con las reservas filtradas
        $commodities = $commoditiesQuery->get();

        // Pasar las fechas seleccionadas a la vista para mantenerlas en el formulario
        return view('reports.commodities', [
            'commodities' => $commodities,
            'start_date' => $startDate ? $startDate->toDateString() : '',
            'end_date' => $endDate ? $endDate->toDateString() : '',
        ]);
    }

    public function getCommodityDetails($id, Request $request)
    {
        // Obtener las fechas de la solicitud
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;

        $commodity = Commodity::with(['bookings' => function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                // Filtrar las reservas por fechas
                $query->whereBetween('booking_commodity.created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                    ->withPivot('created_at');  // Incluye la columna created_at de la tabla pivot
            }
        }, 'bookings.user'])  // Cargar la relación 'user' de los 'bookings'
        ->findOrFail($id);

        return response()->json([
            'title' => $commodity->title,
            'bookings' => $commodity->bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'commodityAddedDate' => $booking->pivot->created_at, // Extrae la fecha de la tabla pivot
                    'user' => [
                        'firstName' => $booking->user->firstName,
                        'lastName' => $booking->user->lastName,
                    ],
                ];
            }),
        ]);
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
