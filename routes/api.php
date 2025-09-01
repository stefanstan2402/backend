<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

//Categories
Route::get('categories/get', [CategoryController::class, 'filter']);
Route::apiResource('categories', CategoryController::class);

//Products
Route::get('products/get', [ProductController::class, 'filter']);
Route::apiResource('products', ProductController::class);


