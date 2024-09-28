<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ResponseController;

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
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::POST('/ticket',[TicketController::class,'store'])->name('ticket.store');

    Route::POST('/response/{id}',[ResponseController::class,'store'])->name('response.store');

    Route::middleware('admin')->group(function () {
        Route::post('/ticket/{id}/close', [TicketController::class, 'close'])->name('ticket.close');

    });

});
