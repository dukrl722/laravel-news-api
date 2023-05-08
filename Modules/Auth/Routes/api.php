<?php

use Modules\Auth\Http\Controllers\AuthController;

Route::prefix('auth')->middleware('api')->group(function () {
    Route::post('register', [AuthController::class, 'create']);
    Route::post('login', [AuthController::class, 'authenticate']);
});

Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});
