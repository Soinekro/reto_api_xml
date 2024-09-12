<?php

namespace App\Http\Controllers\Vouchers\Voucher;

use App\Models\Voucher;
use Illuminate\Http\Response;

/**
 * Class DeleteVoucherHandler
 * @package App\Http\Controllers\Vouchers\Voucher
 */
class DeleteVoucherHandler
{
    /**
     * @param Voucher $voucher
     * @return Response
     */
    public function __invoke(Voucher $voucher): Response
    {
        $voucher->delete();
        return response([
            'message' => 'Voucher deleted successfully',
        ], 200);
    }
}
