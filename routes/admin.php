<?php

use App\Http\Controllers\login;
use Illuminate\Support\Facades\Route;


Route::get('/register', function () {
    return view('admin/UserRegistration'); // View untuk halaman registrasi
});