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

    public function update(Request $request){

        if(isset($request->damageDeposit) && !empty($request->damageDeposit)){
            $policy = Policy::where('description', 'damageDeposit');
            $policy->update(['value' => $request->damageDeposit]);

        }
        elseif(isset($request->basePricePerPersonPerDay) && !empty($request->basePricePerPersonPerDay)){
            $policy = Policy::where('description', 'basePricePerPersonPerDay');
            $policy->update(['value' => $request->basePricePerPersonPerDay]);
        }

        return redirect()->route('policies.index');
    }
}
