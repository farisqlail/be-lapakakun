<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VoucherController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('banners', \App\Http\Controllers\BannerController::class);
    Route::resource('vouchers', \App\Http\Controllers\VoucherController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('contacts', \App\Http\Controllers\ContactController::class);
    Route::resource('chat-templates', \App\Http\Controllers\ChatTemplateController::class);
    Route::resource('members', \App\Http\Controllers\MemberController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('payments', \App\Http\Controllers\PaymentController::class);
    Route::resource('transactions', \App\Http\Controllers\TransactionController::class)->except(['show']);

    Route::get('transactions/history', [\App\Http\Controllers\TransactionController::class, 'history']);
    Route::get('transactions/provide-account', [\App\Http\Controllers\TransactionController::class, 'provideAccount'])->name('transactions.provide');
    Route::post('transactions/{transaction}/provide-account', [\App\Http\Controllers\TransactionController::class, 'provideSingleAccount'])->name('transactions.provide.single');

    Route::resource('accounts', \App\Http\Controllers\AccountController::class);
});

