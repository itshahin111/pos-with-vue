<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\SessionAuthMiddleware;

// Public Routes (No Authentication Required)
Route::get('/', [HomeController::class, 'index'])->name('HomePage');
Route::get('/register', [UserController::class, 'register'])->name('RegistrationPage');
Route::get('/login', [UserController::class, 'userLogin'])->name('LoginPage');
Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('SendOtpPage');
Route::get('/verify-otp', [UserController::class, 'verifyOtp'])->name('VerifyOtpPage');

// User Authentication Routes
Route::post('/user-register', [UserController::class, 'registration'])->name('registration');
Route::post('/user-login', [UserController::class, 'login'])->name('login');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::post('/send-otp', [UserController::class, 'sendOtpCode'])->name('sendOtpCode');
Route::post('/verify-otp-code', [UserController::class, 'verifyOtpCode'])->name('verifyOtpCode');


// Routes That Require Session Authentication
Route::middleware([SessionAuthMiddleware::class])->group(function () {



    Route::get('/reset-password', [UserController::class, 'resetPasswordPage'])->name('ResetPasswordPage');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('resetPassword');

    // User Profile Routes
    Route::post('/user-profile', [UserController::class, 'userProfile'])->name('userProfile');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('updateProfile');

    // Dashboard and Page Routes (Vue)
    Route::get('/dashboard', [DashboardController::class, 'dashboardPage'])->name('dashboardPage');
    Route::get('/user-profile', [UserController::class, 'userProfilePage'])->name('userProfilePage');
    Route::get('/categories', [CategoryController::class, 'categoryPage'])->name('categoryPage');
    Route::get('/customers', [CustomerController::class, 'customerPage'])->name('CustomerPage');
    Route::get('/products', [ProductController::class, 'productPage'])->name('ProductPage');
    Route::get('/sales', [InvoiceController::class, 'salePage'])->name('SalePage');
    Route::get('/invoice', [InvoiceController::class, 'invoicePage'])->name('InvoiceListPage');
    Route::get('/profile', [UserController::class, 'profile'])->name('ProfilePage');

    // Category CRUD Routes
    Route::post('/store-category', [CategoryController::class, 'categoryStore'])->name('storeCategory');
    Route::get('/show-categories', [CategoryController::class, 'showCategories'])->name('showCategories');
    Route::post('/edit-category', [CategoryController::class, 'categoryEdit'])->name('categoryEdit');
    Route::put('/update-category', [CategoryController::class, 'categoryUpdate'])->name('categoryUpdate');
    Route::delete('/delete-category', [CategoryController::class, 'categoryDestroy'])->name('categoryDestroy');

    // Customer CRUD Routes
    Route::post('/store-customer', [CustomerController::class, 'customerStore'])->name('customerStore');
    Route::get('/show-customers', [CustomerController::class, 'showCustomers'])->name('showCustomers');
    Route::post('/edit-customer', [CustomerController::class, 'customerEdit'])->name('customerEdit');
    Route::put('/update-customer', [CustomerController::class, 'customerUpdate'])->name('customerUpdate');
    Route::delete('/delete-customer', [CustomerController::class, 'customerDestroy'])->name('customerDestroy');

    // Product CRUD Routes
    Route::post('/store-product', [ProductController::class, 'storeProduct'])->name('storeProduct');
    Route::get('/show-products', [ProductController::class, 'showProducts'])->name('showProducts');
    Route::post('/edit-product', [ProductController::class, 'editProduct'])->name('editProduct');
    Route::put('/update-product', [ProductController::class, 'updateProduct'])->name('updateProduct');
    Route::delete('/delete-product', [ProductController::class, 'destroyProduct'])->name('deleteProduct');

    // Invoice CRUD Routes
    Route::post('/invoice-create', [InvoiceController::class, 'invoiceCreate'])->name('invoiceCreate');
    Route::get('/invoice-show', [InvoiceController::class, 'invoiceShow'])->name('invoiceShow');
    Route::post('/invoice-details', [InvoiceController::class, 'invoiceDetails'])->name('invoiceDetails');
    Route::delete('/invoice-delete', [InvoiceController::class, 'invoiceDestroy'])->name('invoiceDestroy');

});
