<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DeviseController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\TypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('types', TypeController::class);
    Route::apiResource('devises', DeviseController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    // Product reports
    // Route::get('products/report', [ProductController::class, 'report']);
    Route::get('product_reportss', [ProductController::class,'report']);
    Route::post('products/report', [ProductController::class, 'report']); // allow filters in POST body
    Route::get('products/report/export', [ProductController::class, 'exportReport']);
});
