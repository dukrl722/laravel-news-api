<?php

use Modules\User\Http\Controllers\UserController;

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('info', [UserController::class, 'getUserInfo']);

    Route::post('update', [UserController::class, 'updateProfileInfo']);
    Route::post('update-picture', [UserController::class, 'updateProfilePicture']);
    Route::post('settings', [UserController::class, 'updatePreferenceSettings']);
});
