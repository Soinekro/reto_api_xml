<?php

namespace App\Http\Controllers\Vouchers\Voucher;

use App\Http\Resources\Vouchers\VoucherResource;
use App\Models\Voucher;
use Illuminate\Http\Response;

class GetVoucherHandler
{
    public function __invoke(Voucher $voucher): Response
    {
        return response([
            'data' => VoucherResource::make($voucher),
        ], 200);
    }
}
