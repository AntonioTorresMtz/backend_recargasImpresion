<?php

use App\Http\Controllers\RecargaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('recargas', RecargaController::class);