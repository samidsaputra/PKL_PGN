<?php

use App\Http\Controllers\login;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () { });
Route::get('/', [login::class, 'login'])->name('login');

// rute role admin
require base_path('routes/admin.php');

// rute role aprovel
require base_path('routes/aprove.php');

// rute role admin
require base_path('routes/request.php');