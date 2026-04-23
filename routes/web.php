<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function() {
    return view('login');
})->name('login');

Route::get('/register', function() {
    return view('register');
})->name('register');

Route::get('/transactions', function() {
    return view('transactions');
})->name('transactions')->middleware('auth');

Route::get('/logout', [UserController::class, 'logoutUser'])->name('logout');

// Delete this ⬇
Route::get('/welcome', function() {
    return view('welcome');
});

Route::post('/register', [UserController::class, 'createUser']);
Route::post('/login', [UserController::class, 'loginUser']);