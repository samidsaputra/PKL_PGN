<?php

use App\Http\Controllers\login;
use Illuminate\Support\Facades\Route;

Route::get('/', [login::class, 'index'])->name('login');
Route::post('/', [login::class, 'login']);
Route::get('/login', [login::class, 'index'])->name('login');
Route::post('/login', [login::class, 'login']);
Route::get('/logout', [login::class, 'logout']);



// rute role admin
require base_path('routes/admin.php');

// rute role aprovel
require base_path('routes/aprove.php');

// rute role admin
require base_path('routes/request.php');