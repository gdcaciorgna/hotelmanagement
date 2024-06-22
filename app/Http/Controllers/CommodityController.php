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
        Commodity::create($request->all());
        return redirect()->route('commodities.index');
    }

    public function edit($id) {
        $commodity = Commodity::findOrFail($id);
        return view('commodities.commodityInfo', ['commodity' => $commodity, 'action' => 'edit']);
    }

    public function update($id, Request $request){

        $request->validate([
            'title' => 'required|max:250',
            'description' => 'required|max:1000'
        ]);

        $commodity = Commodity::findOrFail($id);

        $commodity->update(array_merge($request->all()));

        return redirect()->route('commodities.index');
    }
    
    public function destroy(Request $request){
        $commodity = Commodity::findOrFail($request->id);
        $commodity->delete();
        return to_route('commodities.index');
    }

}
