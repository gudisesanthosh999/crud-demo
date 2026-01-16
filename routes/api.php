<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\AuthController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('items', ItemController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
// Route::apiResource('items', ItemController::class)->only([
// 'index','store','update','destroy'
// ]);
