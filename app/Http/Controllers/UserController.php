<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\Commodity;


class UserController extends Controller
{
    public function index(){
        $users = User::query()
            ->orderBy('lastName')
            ->orderBy('firstName')
            ->simplePaginate(50);
        
        return view('users.index')->with('users', $users);
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('users.userInfo', ['user' => $user, 'action' => 'edit']);
    }
    public function create() {
        return view('users.userInfo', ['action' => 'create']);
    }

    public function store(Request $request) {

        //Convert "38.884.376" into "38884376"
        $request->merge([
            'numDoc' => preg_replace('/[^0-9]/', '', $request->numDoc)
        ]); 

        $rules = [
            'firstName' => 'required',
            'lastName' => 'required',
            'bornDate' => 'required|date|before_or_equal:today',
            'numDoc' => [
                'required',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('docType', $request->input('docType'));
                }),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('docType', $request->input('docType'));
                }),
            ],
        ];
        $status = false;

        $arrayDisabled = [];
        if(!$request->has('status')){
            $status = true;
            $arrayDisabled = [
                'disabledStartDate' => null,
                'disabledReason' => null
            ];
        }
        
        $request->validate($rules);
        
        User::create(array_merge($request->all(), ['status' => $status], $arrayDisabled));
        return redirect()->route('users.index');
    }

    public function update($id, Request $request){

        //Convert "38.884.376" into "38884376"
        $request->merge([
            'numDoc' => preg_replace('/[^0-9]/', '', $request->numDoc)
        ]); 

        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'bornDate' => 'required|date|before_or_equal:today',
            'numDoc' => [
                'required',
                Rule::unique('users')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('docType', $request->input('docType'));
                }),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('docType', $request->input('docType'));
                }),
            ],
        ]);

        $user = User::findOrFail($id);
        $status = false;

        $arrayDisabled = [];
        if(!$request->has('status')){
            $status = true;
            $arrayDisabled = [
                'disabledStartDate' => null,
                'disabledReason' => null
            ];
        }

        $user->update(array_merge($request->all(), ['status' => $status], $arrayDisabled));

        return redirect()->route('users.index');
    }

    public function destroy(Request $request){
        $user = User::findOrFail($request->user_id);
        $user->delete();
        return to_route('users.index');
    }

    public function setNewPassword(Request $request){

        //Password Validations
        $validator = Validator::make($request->all(), [
            'newPassword' => 'required|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->errors()->first()]);
        }  
    
        $user = User::findOrFail($request->user_id);
        $user->update(['password' => $request->newPassword]);
        return response()->json(['success' => 'true', 'message' => 'Contraseña actualizada correctamente']);
      
    }

    public function lastBookingCommodities()
    {
        $booking = $this->getLastActiveBooking();
        $bookingCommodities = null;
        $activeCommodities = null;
        $otherCommodities = null;
        if(!empty($booking)){
            // Obtener commodities de la tarifa del booking
            $rateCommodities = $booking->rate->commodities;
        
            // Obtener commodities directamente relacionadas al booking
            $bookingCommodities = $booking->commodities;
        
            // Combinar ambas colecciones
            $activeCommodities = $rateCommodities->merge($bookingCommodities);
        
            // Obtener los IDs de los commodities activos para excluirlos de la siguiente consulta
            $activeCommodityIds = $activeCommodities->pluck('id');
        
            // Obtener otros commodities excluyendo los activos
            $otherCommodities = Commodity::whereNotIn('id', $activeCommodityIds)->get();
        
        }
        return view('commodities.lastBookingCommodities')->with([
            'booking' => $booking,
            'activeCommodities' => $activeCommodities,
            'otherCommodities' => $otherCommodities,
        ]);
    }
        
    public function getLastActiveBooking(): mixed{
        $user = User::findOrFail(auth()->user()->id);
        $lastBooking = $user->bookings()
        ->where('startDate', '<', Carbon::now())
        ->whereNull('actualEndDate')
        ->orderBy('startDate', 'desc') // Orden descendente
        ->first(); // Obtener solo el primer resultado
        return $lastBooking;
    }    
}
