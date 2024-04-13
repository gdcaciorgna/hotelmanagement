<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login.index');
})->name('login.index');

Route::get('/users', function () {
    $users = User::all();

    return view('users.index')->with('users', $users);
})->name('users.index');

Route::get('/users/{id}', function ($id) {
    return "User details {$id}";
})->name('users.details');

Route::get('/users/{id}/edit', function ($id) {
    $user = User::findOrFail($id);
    return  view('users.userInfo', ['user' => $user, 'action' => 'edit']);
})->name('users.edit');

Route::get('/users/create', function () {
    return  view('users.userInfo', ['action' => 'create']);
})->name('users.create');