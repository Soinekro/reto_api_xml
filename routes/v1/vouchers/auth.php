<?php

use App\Http\Controllers\VoucherController;
use App\Http\Controllers\Vouchers\GetTotalByTypeCurrencyHandler;
use Illuminate\Support\Facades\Route;

Route::prefix('vouchers')->group(
    function () {
        Route::get('/total_currency', GetTotalByTypeCurrencyHandler::class);
    }
);
Route::apiResource('vouchers', VoucherController::class)->except('update');
