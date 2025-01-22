<?php

use App\Http\Controllers\requestController;
use App\Http\Controllers\addBarangController;
use App\Http\Controllers\UserReg;
use Illuminate\Support\Facades\Route;


/** Request*/
Route::get('/req/request', [requestController::class, 'request'])->name('request.requester');
Route::post('/checkout', [requestController::class, 'checkout'])->name('cart.checkout');

/** MyRequest */
Route::get('/req/myrequest',[requestController::class, 'history'])->name('request.history');