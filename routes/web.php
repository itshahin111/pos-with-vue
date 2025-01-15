<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('user-register',[UserController::class,'registration'])->name('registration');
Route::post('user-login',[UserController::class,'login'])->name('login');
Route::get('/logout',[UserController::class,'logout'])->name('logout');
Route::post('/forgot-password',[UserController::class,'sendOtpCode'])->name('forgotPassword');

// Route Group with middleware
Route::group(['middleware' => ['TokenVerificationMiddleware']], function () {
    Route::post('/verify-otp-code',[UserController::class,'verifyOtpCode'])->name('verifyOtpCode');
    Route::post('/reset-password',[UserController::class,'ResetPassword'])->name('resetPassword');
    Route::post('/user-profile',[UserController::class,'userProfile'])->name('userProfile');
    Route::post('/update-profile',[UserController::class,'updateProfile'])->name('updateProfile');
});


