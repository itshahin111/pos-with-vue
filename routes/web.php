<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConversionController;
use App\Http\Middleware\SessionAuthMiddleware;
use App\Http\Middleware\TokenVerificationMiddleware;


// User FrontEnd Route
Route::get('/', [HomeController::class, 'index'])->name('HomePage');
Route::get('/register', [UserController::class, 'register'])->name('RegistrationPage');
Route::get('/login', [UserController::class, 'userLogin'])->name('LoginPage');
Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('SendOtpPage');
Route::get('/verify-otp', [UserController::class, 'verifyOtp'])->name('VerifyOtpPage');



// Laravel Vue Page Routing For User Dashboard
Route::get('/user-profile', [UserController::class, 'userProfilePage'])->name('userProfilePage');
Route::get('/categories', [CategoryController::class, 'categoryPage'])->name('categoryPage');
Route::get('/customers', [CustomerController::class, 'customerPage'])->name('CustomerPage');
Route::get('/products', [ProductController::class, 'productPage'])->name('ProductPage');
Route::get('/sales', [InvoiceController::class, 'salePage'])->name('SalePage');
Route::get('/invoice', [InvoiceController::class, 'invoicePage'])->name('InvoiceListPage');
Route::get('/profile', [UserController::class, 'profile'])->name('ProfilePage');

Route::get('/dashboard', [DashboardController::class, 'dashboardPage'])->name('dashboardPage')->middleware([SessionAuthMiddleware::class]);



// User Route Backend
Route::post('/user-register', [UserController::class, 'registration'])->name('registration');
Route::post('/user-login', [UserController::class, 'login'])->name('login');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::post('/forgot-password', [UserController::class, 'sendOtpCode'])->name('forgotPassword');

// Route Group with middleware
Route::group(['middleware' => TokenVerificationMiddleware::class], function () {

//User Route
Route::post('/verify-otp-code', [UserController::class, 'verifyOtpCode'])->name('verifyOtpCode');
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

//Product Route
Route::post('/store-product', [ProductController::class, 'storeProduct'])->name('customerStore');
Route::get('/show-products', [ProductController::class, 'showProducts'])->name('showCustomers');
Route::post('/edit-product', [ProductController::class, 'editProduct'])->name('customerEdit');
Route::put('/update-product', [ProductController::class, 'updateProduct'])->name('customerUpdate');
Route::delete('/delete-product', [ProductController::class, 'destroyProduct'])->name('customerDestroy');


// Invoice Route
Route::post('/invoice-create', [InvoiceController::class, 'invoiceCreate'])->name('invoiceCreate');
Route::get('/invoice-show', [InvoiceController::class, 'invoiceShow'])->name('invoiceShow');
Route::post('/invoice-details', [InvoiceController::class, 'invoiceDetails'])->name('invoiceDetails');
Route::delete('/invoice-delete', [InvoiceController::class, 'invoiceDestroy'])->name('invoiceDestroy');


});
