<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;

Route::post(
    "auth/register",
    [AuthController::class, "register"]
)->name("auth.register");
Route::post(
    "auth/login",
    [AuthController::class, "login"]
)->name("auth.login");

Route::middleware('auth:sanctum')->group(function () {
    Route::get(
        "auth/me",
        [AuthController::class, "me"]
    )->name("auth.me");
    Route::post(
        "auth/logout",
        [AuthController::class, "logout"]
    )->name("auth.logout");

    Route::apiResource(
        'products',
        ProductController::class
    );

    Route::apiResource(
        'products/stock/movements',
        StockMovementController::class
    )->only(['index', 'show', 'destroy']);

    Route::post(
        'products/{id}/stock/in',
        [StockMovementController::class, 'stockIn']
    );
    Route::post(
        'products/{id}/stock/out',
        [StockMovementController::class, 'stockOut']
    );

    Route::apiResource(
        'categories',
        CategoryController::class
    );
});
