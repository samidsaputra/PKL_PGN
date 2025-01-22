<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\addBarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserReg;
use Illuminate\Support\Facades\Route;


//CRUD Barang
Route::get('/admin/daftar-Barang', [addBarangController::class, 'daftarBarang'])->name('daftar.barang');
Route::post('/admin/create-barang', [addBarangController::class, 'createBarang'])->name('create.barang');
Route::put('/admin/update-barang/{id}', [addBarangController::class, 'updateBarang'])->name('update.barang');
Route::delete('/admin/barang/{id}', [addBarangController::class, 'deleteBarang'])->name('delete.barang');


//CRUD Satuan Kerja
Route::get('/admin/satker', [UserReg::class, 'daftarSatker'])->name('daftar.satker');
Route::post('/admin/satker/create', [UserReg::class, 'createSatker'])->name('createSatker');
Route::put('/admin/update-satker/{nama}', [UserReg::class, 'updateSatker'])->name('update.satker');
Route::delete('/admin/delete-satker/{nama}', [UserReg::class, 'deleteSatker'])->name('delete.satker');


//CRUD User Register
Route::get('/admin/UserReg', [UserReg::class, 'UserReg'])->name('UserRegistration');
Route::post('/admin/UserReg', [UserReg::class, 'store'])->name('register.store');


//CRUD Kategori
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::post('/kategori/create', [KategoriController::class, 'store'])->name('kategori.create');
Route::delete('/kategori/delete/{id}', [KategoriController::class, 'destroy'])->name('kategori.delete');


Route::get('/admin/users', [UserReg::class, 'index'])->name('users.index');
Route::get('/admin/users/{user}/edit', [UserReg::class, 'edit'])->name('users.edit');
Route::put('/admin/users/{user}', [UserReg::class, 'update'])->name('users.update');
Route::delete('/admin/users/{user}', [UserReg::class, 'destroy'])->name('users.destroy');

