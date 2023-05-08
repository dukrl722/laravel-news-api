<?php

use Illuminate\Http\Request;
use Modules\News\Http\Controllers\NewsController;

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'getNewsFromNewsApi']);
});
