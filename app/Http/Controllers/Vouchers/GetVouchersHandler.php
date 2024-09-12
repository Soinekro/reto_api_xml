<?php

namespace App\Http\Controllers\Vouchers;

use App\Http\Requests\Vouchers\GetVouchersRequest;
use App\Http\Resources\Vouchers\VoucherResource;
use App\Services\VoucherService;
use Illuminate\Http\Response;

/**
 * Class GetVouchersHandler
 * @package App\Http\Controllers\Vouchers
 *
 */
class GetVouchersHandler
{
    /**
     * GetVouchersHandler constructor.
     * @param VoucherService $voucherService
     * @return void
     */
    public function __construct(private readonly VoucherService $voucherService) {}

    /**
     * @param GetVouchersRequest $request
     * @return Response
     */
    public function __invoke(GetVouchersRequest $request): Response
    {
        $vouchers = $this->voucherService->getVouchers(
            $request->query('page'),
            $request->query('paginate'),
        );

        return response([
            'data' => VoucherResource::collection($vouchers),
        ], 200);
    }
}
