<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\addBarangController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', [adminController::class, 'dashboard'])->name('dashboard');
Route::get('/UserReg', [adminController::class, 'UserReg'])->name('UserRegistration');


//CRUD Barang
Route::get('/daftar-Barang', [addBarangController::class, 'daftarBarang'])->name('daftar.barang');
Route::post('/create-barang', [addBarangController::class, 'createBarang'])->name('create.barang');
Route::post('/update-barang/{id}', [addBarangController::class, 'updateBarang'])->name('update.barang');
Route::delete('/barang/{id}', [addBarangController::class, 'deleteBarang'])->name('delete.barang');
