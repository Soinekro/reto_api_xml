<?php

namespace App\Http\Controllers\Vouchers\Voucher;

use App\Http\Resources\Vouchers\VoucherResource;
use App\Models\Voucher;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetVoucherHandler
{
    public function __construct()
    {
    }

    public function __invoke(Request $request, Voucher $voucher): Response
    {
        return response([
            'data' => VoucherResource::make($voucher),
        ], 200);
    }
}
