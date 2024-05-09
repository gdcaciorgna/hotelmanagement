<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PoliciesController extends Controller
{
    public function index(){
        $damageDepositPolicy = Policy::where('description', 'damageDeposit')->first();
        $damageDeposit = $damageDepositPolicy ? $damageDepositPolicy->value : null;

        $basePricePolicy = Policy::where('description', 'basePricePerPersonPerDay')->first();
        $basePricePerPersonPerDay = $basePricePolicy ? $basePricePolicy->value : null;

        return view('policies.index')->with(['damageDeposit' => $damageDeposit, 'basePricePerPersonPerDay' => $basePricePerPersonPerDay]);
    }    

    public function update($id, Request $request){

        //Convert "38.884.376" into "38884376"
        /*
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
        */
        return redirect()->route('users.index');
    }
}
