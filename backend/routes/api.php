<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIHealthController;

Route::get('/ai/health', [AIHealthController::class, 'test']);