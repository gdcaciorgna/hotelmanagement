<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\PoliciesController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CommodityController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CleaningController;
use App\Http\Controllers\AdditionalServiceController;
use Illuminate\Support\Facades\Auth;


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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//ADMIN SER
Route::middleware(['auth', 'receptionist'])->group(function (){
    //USERS
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/users/create',  [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/setNewPassword', [UserController::class, 'setNewPassword'])->name('users.setNewPassword');   
    
    //HOTEL POLICIES
    Route::get('/policies', [PoliciesController::class, 'index'])->name('policies.index');
    Route::put('/policies', [PoliciesController::class, 'update'])->name('policies.update');
    
    //ROOMS
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
    Route::get('/rooms/create',  [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    //COMMODITIES
    Route::get('/commodities', [CommodityController::class, 'index'])->name('commodities.index');
    Route::get('/commodities/{id}/edit', [CommodityController::class, 'edit'])->name('commodities.edit');
    Route::put('/commodities/{id}', [CommodityController::class, 'update'])->name('commodities.update');
    Route::get('/commodities/create',  [CommodityController::class, 'create'])->name('commodities.create');
    Route::post('/commodities', [CommodityController::class, 'store'])->name('commodities.store');
    Route::delete('/commodities/{id}', [CommodityController::class, 'destroy'])->name('commodities.destroy');

    //RATES
    Route::get('/rates', [RateController::class, 'index'])->name('rates.index');
    Route::get('/rates/{id}/edit', [RateController::class, 'edit'])->name('rates.edit');
    Route::put('/rates/{id}', [RateController::class, 'update'])->name('rates.update');
    Route::get('/rates/create',  [RateController::class, 'create'])->name('rates.create');
    Route::post('/rates', [RateController::class, 'store'])->name('rates.store');
    Route::delete('/rates/{id}', [RateController::class, 'destroy'])->name('rates.destroy');

    //BOOKINGS
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/select-room', [BookingController::class, 'selectRoom'])->name('bookings.selectRoom');
    Route::get('/bookings/{id}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
    Route::get('/bookings/create',  [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/{id}/showCheckout',  [BookingController::class, 'showCheckout'])->name('bookings.showCheckout');
    Route::put('/bookings/{id}/setBookingAsFinished',  [BookingController::class, 'setBookingAsFinished'])->name('bookings.setBookingAsFinished');
    Route::get('/booking/{id}/commodities', [BookingController::class, 'viewExtraCommoditiesForBooking'])->name('bookings.viewExtraCommoditiesForBooking');
    Route::post('/bookings/{id}/addCommodity', [BookingController::class, 'addCommodity'])->name('bookings.addCommodity');
    Route::delete('/bookings/deleteCommodity', [BookingController::class, 'deleteCommodity'])->name('bookings.deleteCommodity');
    Route::get('/booking/{bookingId}/addCommodityToBookingView/{commodity}', [BookingController::class, 'addCommodityToBookingView'])->name('bookings.addCommodityToBookingView');
  
    //CLEANINGS
    Route::get('/receptionist/cleanings', [CleaningController::class, 'index'])->name('receptionistcleanings.index');
    Route::post('/cleanings/requestCleaning', [CleaningController::class, 'requestCleaning'])->name('cleanings.requestCleaning');
    Route::put('/cleanings/finishCleaningAsAdmin', [CleaningController::class, 'finishCleaningAsAdmin'])->name('cleanings.finishCleaningAsAdmin');

    //REPORTS
    Route::get('/commodities-report', [CommodityController::class, 'commoditiesReport'])->name('commodities.report');
    Route::get('/commodities-report/{id}', [CommodityController::class, 'getCommodityDetails'])->name('commodities.details');

    //ADDITIONAL SERVICES
    Route::post('/additionalServices/store', [AdditionalServiceController::class, 'store'])->name('additionalServices.store');
    
});

//CLEANER USERS
Route::middleware(['auth', 'cleaner'])->group(function (){
    Route::get('/cleaner/cleanings', [CleaningController::class, 'index'])->name('cleanercleanings.index');
    Route::put('/cleanings/{id}/startCleaningAsCleaner', [CleaningController::class, 'startCleaningAsCleaner'])->name('cleanings.startCleaningAsCleaner');
    Route::put('/cleanings/{id}/finishCleaningAsCleaner', [CleaningController::class, 'finishCleaningAsCleaner'])->name('cleanings.finishCleaningAsCleaner');
});

//GUEST USERS
Route::middleware(['auth', 'userGuest'])->group(function () {
    Route::get('/guest/lastBookingCommodities', [UserController::class, 'lastBookingCommodities'])->name('lastBookingCommodities.index');
});

Auth::routes();
