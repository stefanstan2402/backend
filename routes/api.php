<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\ApiTokenMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware(ApiTokenMiddleware::class)->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //Categories
    Route::get('categories/get', [CategoryController::class, 'filter']);
    Route::apiResource('categories', CategoryController::class);

    //Products
    Route::get('products/get', [ProductController::class, 'filter']);
    Route::apiResource('products', ProductController::class);
});





