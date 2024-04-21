<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(){
        $users = User::query()
            ->orderBy('fullName')
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
            'dni' => preg_replace('/[^0-9]/', '', $request->dni)
        ]); 

        $rules = [
            'fullName' => 'required',
            'dni' => 'required|unique:users,dni',
        ];

        $request->validate($rules);
        
        User::create($request->all());
        return redirect()->route('users.index');
    }

    public function update($id, Request $request){

        //Convert "38.884.376" into "38884376"
        $request->merge([
            'dni' => preg_replace('/[^0-9]/', '', $request->dni)
        ]); 
        
        $request->validate([
            'fullName' => 'required',
            'dni' => ['required', Rule::unique('users')->ignore($id)] //unsuccessfull process
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
    
}
