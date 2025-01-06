<?php

use App\Http\Controllers\login;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () { });
Route::get('/', [login::class, 'login'])->name('login');
