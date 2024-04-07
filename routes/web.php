<?php

use Illuminate\Support\Facades\Route;

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
    return view('users.index');
})->name('users.index');

Route::get('/users/{id}', function ($id) {
    return "User details {$id}";
})->name('users.details');

Route::get('/users/create', function () {
    return view('users.create');
})->name('users.create');

Route::get('/users/{id}/edit', function ($id) {
    return  "Edit user {$id}";
})->name('users.edit');

