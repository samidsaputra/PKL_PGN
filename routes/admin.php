<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\login;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', [adminController::class, 'dashboard'])->name('dashboard');
Route::get('/UserReg', [adminController::class, 'UserReg'])->name('UserRegistration');
