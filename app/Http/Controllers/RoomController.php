<?php

namespace App\Http\Controllers;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(){
        $rooms = Room::query()
        ->orderBy('code')
        ->simplePaginate(30);
        
        return view('rooms.index')->with('rooms', $rooms);
    }

    public function create() {
        return view('rooms.roomInfo', ['action' => 'create']);
    }

    public function store(Request $request) {
        $rules = [
            'code' => 'required|integer|min:1',
            'maxOfGuests' => 'required|integer|min:1',
            'description' => 'required',
            'status' => 'required'
        ];

        $request->validate($rules);
        dd($request);
        Room::create($request->all());
        return redirect()->route('rooms.index');
    }

}
