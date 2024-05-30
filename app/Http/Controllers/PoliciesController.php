<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoliciesController extends Controller
{
    public function index(){
        $damageDepositPolicy = Policy::where('description', 'damageDeposit')->first();
        $damageDeposit = $damageDepositPolicy ? $damageDepositPolicy->value : null;

        $basePricePolicy = Policy::where('description', 'basePricePerPersonPerDay')->first();
        $basePricePerPersonPerDay = $basePricePolicy ? $basePricePolicy->value : null;

        $cleaningWorkingHoursFromPolicy = Policy::where('description', 'cleaningWorkingHoursFrom')->first();
        $cleaningWorkingHoursFrom = $cleaningWorkingHoursFromPolicy ? $cleaningWorkingHoursFromPolicy->value : null;

        $cleaningWorkingHoursToPolicy = Policy::where('description', 'cleaningWorkingHoursTo')->first();
        $cleaningWorkingHoursTo = $cleaningWorkingHoursToPolicy ? $cleaningWorkingHoursToPolicy->value : null;

        return view('policies.index')->with([
            'damageDeposit' => $damageDeposit, 
            'basePricePerPersonPerDay' => $basePricePerPersonPerDay, 
            'cleaningWorkingHoursFrom' => $cleaningWorkingHoursFrom,
            'cleaningWorkingHoursTo' => $cleaningWorkingHoursTo
        ]);
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

        elseif(isset($request->cleaningWorkingHoursFrom) && !empty($request->cleaningWorkingHoursFrom) && isset($request->cleaningWorkingHoursTo) && !empty($request->cleaningWorkingHoursTo)){

            $validator = Validator::make($request->all(), [
                'cleaningWorkingHoursFrom' => 'required|date_format:H:i',
                'cleaningWorkingHoursTo' => 'required|date_format:H:i|after:cleaningWorkingHoursFrom',
            ], [
                'cleaningWorkingHoursTo.after' => 'La hora de finalización del horario laboral de limpieza debe ser posterior a la hora de inicio.',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
                
            $policy = Policy::where('description', 'cleaningWorkingHoursFrom');
            $policy->update(['value' => $request->cleaningWorkingHoursFrom]);

            $policy = Policy::where('description', 'cleaningWorkingHoursTo');
            $policy->update(['value' => $request->cleaningWorkingHoursTo]);

        }

        return redirect()->route('policies.index');
    }
}
