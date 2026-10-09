<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Endpoint JSON ini memakai session login Laravel dan perlindungan CSRF web.
    Route::prefix('api')->name('api.')->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
        Route::post('/sales/{sale}/confirm-payment', [SaleController::class, 'confirmPayment'])->name('sales.confirm');
        Route::get('/stock-entries', [StockEntryController::class, 'index'])->name('stock.index');
        Route::post('/stock-entries', [StockEntryController::class, 'store'])->name('stock.store');
        Route::get('/cash-flows', [CashFlowController::class, 'index'])->name('cash-flows.index');
        Route::post('/cash-flows', [CashFlowController::class, 'store'])->name('cash-flows.store');

        Route::middleware('owner')->group(function (): void {
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::patch('/products/{variant}', [ProductController::class, 'update'])->name('products.update');
            Route::get('/reports', ReportController::class)->name('reports');
            Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
            Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        });
    });
});
