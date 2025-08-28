<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Roles
    Route::apiResource('roles', RoleController::class);

    // Categorías
    Route::apiResource('categories', CategoryController::class);

    // Productos
    Route::apiResource('products', ProductController::class);

});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

