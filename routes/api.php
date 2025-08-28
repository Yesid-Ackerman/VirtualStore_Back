<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

// ----------------- AUTENTICACIÓN -----------------
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ----------------- CRUD USUARIOS (solo ADMIN) -----------------
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('users', UserController::class);
});

// ----------------- CRUD ROLES (solo ADMIN) -----------------
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('roles', RoleController::class);
});

// ----------------- CRUD CATEGORÍAS (ADMIN, MANAGER) -----------------
Route::middleware(['auth:sanctum', 'role:admin,manager'])->group(function () {
    Route::apiResource('categories', CategoryController::class);
});

// ----------------- CRUD PRODUCTOS -----------------
Route::middleware(['auth:sanctum', 'role:admin,manager'])->group(function () {
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});

// ✅ VISUALIZAR PRODUCTOS (todos los roles autenticados)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);
});
