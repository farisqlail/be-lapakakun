<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home
');

Route::resource('users', \App\Http\Controllers\UserController::class);
Route::resource('banners', \App\Http\Controllers\BannerController::class);
Route::resource('vouchers', \App\Http\Controllers\VoucherController::class);
Route::resource('categories', \App\Http\Controllers\CategoryController::class);


