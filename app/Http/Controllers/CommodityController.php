<?php

namespace App\Http\Controllers;
use App\Models\Commodity;

use Illuminate\Http\Request;

class CommodityController extends Controller
{
    public function index(){
        $commodities = Commodity::query()
        ->simplePaginate(30);
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
        $commodity->delete();
        return to_route('commodities.index');
    }

}
