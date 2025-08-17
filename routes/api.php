<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;

Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::post("logout", [AuthController::class, "logout"]);

    Route::apiResource('products', ProductController::class);

    Route::apiResource(
        'products/stock/movements',
        StockMovementController::class
    )->only(['index', 'show', 'destroy']);

    Route::post('products/{id}/stock/in', [StockMovementController::class, 'stockIn']);
    Route::post('products/{id}/stock/out', [StockMovementController::class, 'stockOut']);

    Route::apiResource('categories', CategoryController::class);
});
