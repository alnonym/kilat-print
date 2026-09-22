<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, CustomerController, AdminController, OperatorController};

// PUBLIC ROUTES
Route::get('/', [CustomerController::class, 'index'])->name('home');
Route::get('/product/{slug}', [CustomerController::class, 'show'])->name('product.show');

// AUTH ROUTES
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// CUSTOMER ROUTES (pelanggan only)
Route::middleware(['auth', 'role:pelanggan'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
    Route::post('/order', [CustomerController::class, 'processOrder'])->name('process-order');
    Route::post('/order/{id}/payment', [CustomerController::class, 'uploadPaymentProof'])->name('upload-payment');
    Route::get('/tracking/{order_number}', [CustomerController::class, 'tracking'])->name('tracking');
});

// ADMIN ROUTES (admin only)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::post('/order/{id}/verify-payment', [AdminController::class, 'verifyPayment'])->name('verify-payment');
    Route::post('/production/{production_id}/assign-operator', [AdminController::class, 'assignOperator'])->name('assign-operator');
});

// OPERATOR ROUTES (operator only)
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/queue', [OperatorController::class, 'index'])->name('queue');
    Route::get('/order/{id}', [OperatorController::class, 'show'])->name('show');
    Route::post('/order/{id}/status', [OperatorController::class, 'updateStatus'])->name('update-status');
});
