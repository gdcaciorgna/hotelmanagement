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
            'description' => 'required|max:1000'
        ];

        $request->validate($rules);
        Room::create($request->all());
        return redirect()->route('rooms.index');
    }

    public function edit($id) {
        $room = Room::findOrFail($id);
        return view('rooms.roomInfo', ['room' => $room, 'action' => 'edit']);
    }

    public function update($id, Request $request){

        $request->validate([
            'code' => 'required|integer|min:1',
            'maxOfGuests' => 'required|integer|min:1',
            'description' => 'required|max:1000'
        ]);

        $room = Room::findOrFail($id);

        $room->update(array_merge($request->all()));

        return redirect()->route('rooms.index');
    }
    
    public function destroy(Request $request){
        $room = Room::findOrFail($request->id);
        $room->delete();
        return to_route('rooms.index');
    }


}
