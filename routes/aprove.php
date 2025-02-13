<?php

use App\Http\Controllers\approveController;
use App\Http\Controllers\addBarangController;
use App\Http\Controllers\UserReg;
use Illuminate\Support\Facades\Route;


/** aproval*/
Route::get('/apr/approval', [approveController::class, 'approve'])->name('approver.approval');
Route::get('/orders/{noorder}', [approveController::class, 'show'])->name('orders.show');


Route::get('/apr/approved', [approveController::class, 'approveHistory'])->name('approver.approved');

Route::put('/orders/{noorder}/update-status', [approveController::class, 'updateStatus']);

Route::get('/orders/{noorder}/edit', [approveController::class, 'edit'])->name('orders.edit');
Route::post('/orders/{noorder}/update-order', [approveController::class, 'update'])->name('orders.update');