<?php

use App\Http\Controllers\RecargaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('recargas', RecargaController::class);
Route::get('/prueba', [RecargaController::class, 'prueba']);
Route::get('/recargas', [RecargaController::class, 'index']);