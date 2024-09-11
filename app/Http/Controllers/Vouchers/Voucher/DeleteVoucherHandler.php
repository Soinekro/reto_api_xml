<?php

namespace App\Http\Controllers\Vouchers\Voucher;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteVoucherHandler
{
    public function __construct()
    {
    }

    public function __invoke(Request $request, Voucher $voucher): Response
    {
        $voucher->delete();
        return response([
            'message' => 'Voucher deleted successfully',
        ], 200);
    }
}
