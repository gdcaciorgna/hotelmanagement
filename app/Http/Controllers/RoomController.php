<?php

namespace App\Http\Controllers;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'description' => 'required|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192'
        ];
        $request->validate($rules);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');            
            $imageName = 'room' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/rooms'), $imageName);
    
            Room::create([
                'code' => $request->input('code'),
                'maxOfGuests' => $request->input('maxOfGuests'),
                'description' => $request->input('description'),
                'image' => $imageName
            ]);
        } else {
            Room::create($request->all());
        }
    
        return redirect()->route('rooms.index');
    }

    public function edit($id) {
        $room = Room::findOrFail($id);
        return view('rooms.roomInfo', ['room' => $room, 'action' => 'edit']);
    }
    public function update($id, Request $request){
        $room = Room::findOrFail($id);
    
        $request->validate([
            'code' => 'required|integer|min:1',
            'maxOfGuests' => 'required|integer|min:1',
            'description' => 'required|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'status' => 'nullable|string'
        ]);
    
        if ($request->has('delete_image') && $request->delete_image) {
            if ($room->image) {
                Storage::delete(public_path('img/rooms/' . $room->image));
                $room->image = null;
            }
        }
    
        if ($request->hasFile('image')) {
            if ($room->image) {
                Storage::delete(public_path('img/rooms/' . $room->image));
            }
            $image = $request->file('image');
            $imageName = 'room' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/rooms'), $imageName);
            $room->image = $imageName;
        }
    
        $room->code = $request->code;
        $room->maxOfGuests = $request->maxOfGuests;
        $room->description = $request->description;
        $room->status = $request->status;
        $room->save();
    
        return redirect()->route('rooms.edit', $room->id)->with('success', 'Habitación actualizada correctamente.');
    }
    public function destroy(Request $request){
        $room = Room::findOrFail($request->id);
        $room->delete();
        return to_route('rooms.index');
    }


}
