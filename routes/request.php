<?php

use App\Http\Controllers\requestController;
use App\Http\Controllers\addBarangController;
use App\Http\Controllers\UserReg;
use Illuminate\Support\Facades\Route;


/** Request*/
Route::get('/req/request', [requestController::class, 'request'])->name('request.requester');
Route::post('/cart/checkout', [requestController::class, 'checkout'])->name('cart.checkout');

/** MyRequest */
Route::get('/req/myrequest',[requestController::class, 'history'])->name('request.history');
Route::get('/req/myrequest/detail/{noorder}',[requestController::class, 'show'])->name('request.show');
Route::get('/req/myrequest/{noorder}/edit', [requestController::class, 'edit'])->name('requester.orders.edit');
Route::post('/req/myrequest/{noorder}/update-order', [requestController::class, 'update'])->name('requester.orders.update');