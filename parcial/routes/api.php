<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DonacionController;
use App\Http\Controllers\API\DonanteController;
use App\Http\Controllers\API\AdministradorController;

Route::get('/donaciones', [DonacionController::class, 'index']);
Route::post('/donaciones', [DonacionController::class, 'store']);
Route::put('/donaciones/{id}', [DonacionController::class, 'update']);
Route::delete('/donaciones/{id}', [DonacionController::class, 'destroy']);

Route::get('/donantes', [DonanteController::class, 'index']);
Route::get('/administradores', [AdministradorController::class, 'index']);
