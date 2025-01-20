<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\addBarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserReg;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', [adminController::class, 'dashboard'])->name('dashboard');

//CRUD Barang
Route::get('/daftar-Barang', [addBarangController::class, 'daftarBarang'])->name('daftar.barang');
Route::post('/create-barang', [addBarangController::class, 'createBarang'])->name('create.barang');
Route::put('/barang/update/{id}', [addBarangController::class, 'updateBarang'])->name('update.barang');
Route::delete('/barang/{id}', [addBarangController::class, 'deleteBarang'])->name('delete.barang');

//CRUD Satuan Kerja
Route::get('/satker', [UserReg::class, 'daftarSatker'])->name('daftar.satker');
Route::post('/satker/create', [UserReg::class, 'createSatker'])->name('createSatker');
Route::put('/update-satker/{nama}', [UserReg::class, 'updateSatker'])->name('update.satker');
Route::delete('/delete-satker/{nama}', [UserReg::class, 'deleteSatker'])->name('delete.satker');


//CRUD User Register
Route::get('/UserReg', [UserReg::class, 'UserReg'])->name('UserRegistration');
Route::post('/UserReg', [UserReg::class, 'store'])->name('register.store');

Route::get('/users', [UserReg::class, 'index'])->name('users.index');
Route::get('/users/{user}/edit', [UserReg::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserReg::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserReg::class, 'destroy'])->name('users.destroy');

//CRUD Kategori
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::post('/kategori/create', [KategoriController::class, 'store'])->name('kategori.create');
Route::delete('/kategori/delete/{id}', [KategoriController::class, 'destroy'])->name('kategori.delete');