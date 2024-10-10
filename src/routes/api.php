<?php

use Illuminate\Support\Facades\Route;
use Fmcpay\Config\Http\ConfigController;



Route::post('/listConfig', [ConfigController::class, 'index']);
Route::post('/createConfig', [ConfigController::class, 'createConfig']);
Route::post('/destroy', [ConfigController::class, 'destroy']);
Route::post('/update', [ConfigController::class, 'update']);
Route::post('/toggleStatus', [ConfigController::class, 'toggleStatus']);
