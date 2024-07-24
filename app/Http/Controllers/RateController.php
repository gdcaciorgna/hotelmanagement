<?php

namespace App\Http\Controllers;
use App\Models\Rate;

use Illuminate\Http\Request;

class RateController extends Controller
{
    public function index(){
        $rates = Rate::query()
        ->simplePaginate(30);
        
        return view('rates.index')->with('rates', $rates);
    }

    public function create() {
        return view('rates.rateInfo', ['action' => 'create']);
    }

    public function store(Request $request) {
        $rules = [
            'title' => 'required|max:250',
            'description' => 'required|max:1000'
        ];

        $request->validate($rules);
        Rate::create($request->all());
        return redirect()->route('rates.index');
    }

    public function edit($id) {
        $rate = Rate::findOrFail($id);
        return view('rates.rateInfo', ['rate' => $rate, 'action' => 'edit']);
    }

    public function update($id, Request $request){

        $request->validate([
            'title' => 'required|max:250',
            'description' => 'required|max:1000',
            'currentPrice' => 'required|numeric'

        ]);

        $rate = Rate::findOrFail($id);
        $rate->update($request->except('currentPrice'));
        $rate->updateCurrentPrice($request->input('currentPrice'));

        return redirect()->route('rates.index');
    }
    
    public function destroy(Request $request){
        $rate = Rate::findOrFail($request->id);
        $rate->delete();
        return to_route('rates.index');
    }

}
