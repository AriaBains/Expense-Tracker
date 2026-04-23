<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories/create', [CategoryController::class, 'create']);
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->where('category', '[0-9]+');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->where('category', '[0-9]+');

    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions/create', [TransactionController::class, 'create']);
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->where('transaction', '[0-9]+');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->where('transaction', '[0-9]+');
});