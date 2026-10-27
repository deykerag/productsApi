<?php

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPriceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class);

    Route::apiResource('currencies', CurrencyController::class)
        ->only(['index', 'store', 'destroy']);

    Route::get(
        'products/{product}/prices',
        [ProductPriceController::class, 'index']
    );

    Route::post(
        'products/{product}/prices',
        [ProductPriceController::class, 'store']
    );
});
