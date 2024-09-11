<?php

namespace App\Http\Controllers\Vouchers\Voucher;

use App\Models\Voucher;
use Illuminate\Http\Response;

class DeleteVoucherHandler
{
    public function __invoke(Voucher $voucher): Response
    {
        $voucher->delete();
        return response([
            'message' => 'Voucher deleted successfully',
        ], 200);
    }
}
