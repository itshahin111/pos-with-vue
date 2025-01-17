<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\TokenVerificationApiMiddleware;

Route::post('/user-register', [UserController::class, 'registration'])->name('registration');
Route::post('/user-login', [UserController::class, 'login'])->name('login');
Route::get('/logout', [UserController::class, 'logout'])->name('logout'); // Its use for api testing
Route::post('/forgot-password', [UserController::class, 'sendOtpCode'])->name('forgotPassword');

// Route Group with middleware
Route::group(['middleware' => TokenVerificationApiMiddleware::class], function () {
    Route::post('/verify-otp', [UserController::class, 'verifyOtpCode'])->name('verifyOtpCode');
    Route::post('/reset-password', [UserController::class, 'ResetPassword'])->name('resetPassword');
    Route::post('/user-profile', [UserController::class, 'userProfile'])->name('userProfile');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('updateProfile');

    // Category Route
    Route::post('/store', [CategoryController::class, 'categoryStore'])->name('storeCategory');
    Route::get('/show', [CategoryController::class, 'showCategories'])->name('showCategories');
    Route::post('/edit', [CategoryController::class, 'categoryEdit'])->name('categoryEdit');
    Route::put('/update', [CategoryController::class, 'categoryUpdate'])->name('categoryUpdate');
    Route::delete('/delete', [CategoryController::class, 'categoryDestroy'])->name('categoryDestroy');



    // Customer Route
    Route::post('/store-customer', [CustomerController::class, 'customerStore'])->name('customerStore');
    Route::get('/show-customers', [CustomerController::class, 'showCustomers'])->name('showCustomers');
    Route::post('/edit-customer', [CustomerController::class, 'customerEdit'])->name('customerEdit');
    Route::put('/update-customer', [CustomerController::class, 'customerUpdate'])->name('customerUpdate');
    Route::delete('/delete-customer', [CustomerController::class, 'customerDestroy'])->name('customerDestroy');

});

