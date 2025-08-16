<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

Route::post(
    "register",
    [
        AuthController::class,
        "register"
    ]
);
Route::post(
    "login",
    [
        AuthController::class,
        "login"
    ]
);
Route::post(
    "logout",
    [AuthController::class, "logout"]
)->middleware('auth:sanctum');

Route::apiResource(
    'products',
    ProductController::class
)->middleware('auth:sanctum');
