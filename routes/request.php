<?php

use App\Http\Controllers\requestController;
use App\Http\Controllers\addBarangController;
use App\Http\Controllers\UserReg;
use Illuminate\Support\Facades\Route;


Route::get('/req/request', [requestController::class, 'request'])->name('request.requester');