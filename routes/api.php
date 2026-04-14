<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContainerController;
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
    Route::post('products/{product}/archive', [ProductController::class, 'archive']);
    Route::post('products/{product}/unarchive', [ProductController::class, 'unarchive']);
    Route::post('products/bulk-archive', [ProductController::class, 'bulkArchive']);
    Route::post('products/bulk-unarchive', [ProductController::class, 'bulkUnarchive']);
    Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete']);
    
    Route::apiResource('suppliers', SupplierController::class);
    Route::get('containers', [ContainerController::class, 'index']);
    Route::get('containers/{id}', [ContainerController::class, 'show']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);
    Route::get('/users', [AuthController::class, 'getUsers']);
    Route::post('/users', [AuthController::class, 'addUser']);
    Route::put('/users/{user}', [AuthController::class, 'updateUserData']);
    // Product reports
    // Route::get('products/report', [ProductController::class, 'report']);
    Route::get('product_reportss', [ProductController::class,'report']);
    Route::post('products/report', [ProductController::class, 'report']); // allow filters in POST body
    Route::get('products/report/export', [ProductController::class, 'exportReport']);
    Route::get('products/report/pdf', [ProductController::class, 'exportPdfReport']);
});
