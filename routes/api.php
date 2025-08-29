<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockEntryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ---------------- ADMIN ONLY (usuarios y roles) ----------------
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Users
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    // Roles
    Route::get('roles', [RoleController::class, 'index']);
    Route::get('roles/{role}', [RoleController::class, 'show']);
    Route::post('roles', [RoleController::class, 'store']);
    Route::put('roles/{role}', [RoleController::class, 'update']);
    Route::delete('roles/{role}', [RoleController::class, 'destroy']);
});

// --------------- ADMIN & MANAGER (crear/editar/eliminar categorías y productos) ---------------
Route::middleware(['auth:sanctum', 'role:admin,manager'])->group(function () {
    // Categories - write
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);

    // Products - write
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});

// --------------- READ-ONLY (ADMIN, MANAGER, VENDEDOR) ---------------
Route::middleware(['auth:sanctum', 'role:admin,manager,vendedor'])->group(function () {
    // Categories - read
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    // Products - read
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:admin,manager,vendedor'])->group(function () {
    Route::post('sales', [SaleController::class, 'store']);   // Registrar venta
    Route::get('sales', [SaleController::class, 'index']);   // Listar ventas
    Route::get('sales/{sale}', [SaleController::class, 'show']); // Detalle de venta
});

Route::middleware('auth:sanctum')->post('stock-entry', [StockEntryController::class, 'store']);

Route::middleware('auth:sanctum')->get('logs', function() {
    return \App\Models\Log::with('user')->orderBy('created_at','desc')->paginate(20);
});
Route::get('/history', [SaleController::class, 'history']);
