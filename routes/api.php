<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockEntryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ---------- AUTH ----------
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ---------- PRODUCTS & CATEGORIES ----------
Route::middleware(['auth:sanctum', 'role:admin,manager,vendedor'])->group(function () {
    // Categories
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    // Products
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);
});

// Solo admin y manager pueden crear/editar/eliminar
Route::middleware(['auth:sanctum', 'role:admin,manager'])->group(function () {
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);

    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);

    // ---------- STOCK ENTRY ----------
Route::middleware('auth:sanctum')->post('stock-entry', [StockEntryController::class, 'store']);
Route::get('/sales', [SaleController::class, 'index']);
});

// ---------- SALES ----------
Route::middleware(['auth:sanctum', 'role:admin,manager,vendedor'])->group(function () {
    Route::post('sales', [SaleController::class, 'store']);        // Registrar venta
    // Route::get('sales', [SaleController::class, 'index']);         // Historial de ventas
    Route::get('sales/{sale}', [SaleController::class, 'show']);   // Detalle de una venta
    Route::get('sales-summary', [SaleController::class, 'dailySummary']);  // Resumen diario
});


