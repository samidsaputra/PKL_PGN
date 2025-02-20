<?php

use App\Http\Controllers\login;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AutoLogout;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;

// Login routes
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post'); // Tambahkan route POST untuk login
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Dashboard routes (protected by auth middleware)
Route::middleware([AutoLogout::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Load routes for specific roles
    require base_path('routes/admin.php');
    require base_path('routes/aprove.php');
    require base_path('routes/request.php');
});