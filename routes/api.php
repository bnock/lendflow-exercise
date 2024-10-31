<?php

use App\Http\Controllers\NytController;
use Illuminate\Support\Facades\Route;

Route::name('v1.')->prefix('1')->group(function () { // API v1
    Route::name('nyt.')->prefix('nyt')->controller(NytController::class)->group(function () { // NYT API
        Route::get('best-sellers', 'bestSellers')->name('best-sellers');
    });
});
