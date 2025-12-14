<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MyAdsController;
use App\Http\Controllers\Api\V1\StoreAdController;
use App\Http\Controllers\Api\V1\ShowAdController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/ads', [StoreAdController::class, 'store']);
        Route::put('/ads/{ad}', [StoreAdController::class, 'update']);
        Route::delete('/ads/{ad}', [StoreAdController::class, 'destroy']);
        Route::get('/my-ads', [MyAdsController::class, 'index']);
    });

    // Public ads route
    Route::get('/ads/{ad}', [ShowAdController::class, 'show']);
});
