<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AIHealthController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonAttributeController;

Route::get('/ai/health', [AIHealthController::class, 'test']);

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/people', [PersonController::class, 'index']);
    Route::post('/people', [PersonController::class, 'store']);
    Route::get('/people/{person}', [PersonController::class, 'show']);
    Route::put('/people/{person}', [PersonController::class, 'update']);
    Route::delete('/people/{person}', [PersonController::class, 'destroy']);
    Route::get('/people/{person}/attributes', [PersonAttributeController::class, 'index']);
    Route::post('/people/{person}/attributes', [PersonAttributeController::class, 'store']);
    Route::put('/people/{person}/attributes/{attribute}', [PersonAttributeController::class, 'update']);
    Route::delete('/people/{person}/attributes/{attribute}', [PersonAttributeController::class, 'destroy']);
});
