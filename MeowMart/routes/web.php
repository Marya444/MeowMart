<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Manager and Admin Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin,manager'])
    ->name('dashboard');

// New route for fetching low stock products via AJAX
Route::get('/dashboard/low-stock-products', [DashboardController::class, 'getLowStockProducts'])
    ->middleware(['auth', 'verified', 'role:admin,manager'])
    ->name('dashboard.lowStockProducts');

// Cashier Dashboard
Route::get('/cashier-dashboard', [DashboardController::class, 'cashierDashboard'])
    ->middleware(['auth', 'verified', 'role:cashier'])
    ->name('cashier.dashboard');

// Profile routes (All Authenticated Users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User Management Routes (Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Product Management & ALL Reports/History Routes (Admin/Manager)
Route::middleware(['auth', 'role:admin,manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    // ALL Sales Reports and Transaction History for Admins and Managers
    // These routes will be prefixed with /admin/reports and named admin.reports.*
    Route::prefix('reports')->name('reports.')->group(function () {
        // Main report page with flexible date filtering (handles daily, weekly, monthly, quarterly, custom)
        Route::get('/', [TransactionController::class, 'report'])->name('index');
        // Specific links for different periods if desired (will call the same report method, or separate ones if you like)
        Route::get('/daily', [TransactionController::class, 'daily'])->name('daily'); // Daily history for managers/admins
        Route::get('/weekly', [TransactionController::class, 'weekly'])->name('weekly');
        Route::get('/monthly', [TransactionController::class, 'monthly'])->name('monthly');
        Route::get('/quarterly', [TransactionController::class, 'quarterly'])->name('quarterly');
        // View single transaction details
        Route::get('/transactions/{order}', [TransactionController::class, 'show'])->name('transactions.show');
    });
});

// POS Routes (All Authenticated Users)
Route::middleware(['auth'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [\App\Http\Controllers\POSController::class, 'index'])->name('index');
    Route::post('/cart/add', [\App\Http\Controllers\POSController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update', [\App\Http\Controllers\POSController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove', [\App\Http\Controllers\POSController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [\App\Http\Controllers\POSController::class, 'clearCart'])->name('cart.clear');
    Route::post('/checkout', [\App\Http\Controllers\POSController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/confirm', [\App\Http\Controllers\POSController::class, 'confirmOrder'])->name('checkout.confirm');
    Route::post('/cart/discount', [\App\Http\Controllers\POSController::class, 'applyDiscount'])->name('cart.applyDiscount');
});

// New route for refreshing stock data via AJAX
Route::get('/dashboard/refresh-stock', [DashboardController::class, 'refreshStock'])
    ->middleware(['auth', 'verified', 'role:admin,manager'])
    ->name('dashboard.refreshStock');

require __DIR__ . '/auth.php';
