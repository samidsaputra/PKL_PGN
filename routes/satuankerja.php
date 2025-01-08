<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\login;
use App\Http\Controllers\UserReg;
use Illuminate\Support\Facades\Route;




Route::get('/satker', [UserReg::class, 'daftarSatker'])->name('daftar.satker');
Route::post('/satker/create', [UserReg::class, 'createSatker'])->name('createSatker');
Route::put('/update-satker/{nama}', [UserReg::class, 'updateSatker'])->name('update.satker');
Route::delete('/delete-satker/{nama}', [UserReg::class, 'deleteSatker'])->name('delete.satker');


Route::get('/register', [UserReg::class, 'index'])->name('register');
Route::post('/register', [UserReg::class, 'store'])->name('register.store');

// You might want to add these additional user management routes:
Route::get('/users', [UserReg::class, 'index'])->name('users.index');
Route::get('/users/{user}/edit', [UserReg::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserReg::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserReg::class, 'destroy'])->name('users.destroy');