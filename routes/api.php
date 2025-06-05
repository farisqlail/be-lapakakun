<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\VoucherController;
use Illuminate\Support\Facades\Route;

//auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('api')->group(function () {
    //profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    //products
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);

    //categories
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    //vouchers
    Route::get('vouchers', [VoucherController::class, 'index']);
    Route::get('vouchers/{id}', [VoucherController::class, 'show']);

    Route::apiResource('transactions', TransactionController::class);

    // Additional transaction routes
    Route::get('transactions/{transaction}/edit', [TransactionController::class, 'edit'])
        ->name('api.transactions.edit');
    Route::get('transactions-history', [TransactionController::class, 'history'])
        ->name('api.transactions.history');
    Route::post('transactions/provide-accounts', [TransactionController::class, 'provideAccounts'])
        ->name('api.transactions.provide-accounts');
    Route::post('transactions/{transaction}/provide-account', [TransactionController::class, 'provideSingleAccount'])
        ->name('api.transactions.provide-single-account');
    Route::get('transactions-statistics', [TransactionController::class, 'statistics'])
        ->name('api.transactions.statistics');
});
