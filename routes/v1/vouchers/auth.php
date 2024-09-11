<?php

use App\Http\Controllers\Vouchers\GetTotalByTypeCurrencyHandler;
use App\Http\Controllers\Vouchers\GetVouchersHandler;
use App\Http\Controllers\Vouchers\StoreVouchersHandler;
use App\Http\Controllers\Vouchers\Voucher\DeleteVoucherHandler;
use App\Http\Controllers\Vouchers\Voucher\GetVoucherHandler;
use Illuminate\Support\Facades\Route;

Route::prefix('vouchers')->group(
    function () {
        Route::get('/', GetVouchersHandler::class);
        Route::post('/', StoreVouchersHandler::class);
        Route::put('/{voucher}', GetVoucherHandler::class);
        Route::delete('/{voucher}', DeleteVoucherHandler::class);
        Route::get('/total_currency', GetTotalByTypeCurrencyHandler::class);
    }
);
