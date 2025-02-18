<?php

use App\Http\Controllers\login;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;

// Login routes
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post'); // Tambahkan route POST untuk login
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// rute role admin
require base_path('routes/admin.php');

// rute role aprovel
require base_path('routes/aprove.php');

// rute role admin
require base_path('routes/request.php');