<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vouchers\GetVouchersRequest;
use App\Http\Resources\Vouchers\VoucherResource;
use App\Jobs\Vouchers\ProcessVouchersFromXmlContentsJob;
use App\Models\Voucher;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VoucherController extends Controller
{
    /**
     * VoucherController constructor.
     * @param VoucherService $voucherService
     * @return void
     */
    public function __construct(private readonly VoucherService $voucherService) {}
    /**
     * Display a listing of the resource.
     * @param GetVouchersRequest $request
     * @return Response
     */
    public function index(GetVouchersRequest $request): Response
    {
        $vouchers = $this->voucherService->getVouchers();

        return response([
            'data' => VoucherResource::collection($vouchers),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $xmlFiles = $request->file('files');
        if (!is_array($xmlFiles)) {
            $xmlFiles = [$xmlFiles];
        }
        $xmlContents = [];
        $user = auth()->user();
        foreach ($xmlFiles as $xmlFile) {
            $xmlContents[] = file_get_contents($xmlFile->getRealPath());
        }
        ProcessVouchersFromXmlContentsJob::dispatch($xmlContents, $user);
        return response([
            'data' => 'Los comprobantes se están procesando',
        ], 201);
    }

    /**
     * Display the specified resource.
     * @param Voucher $voucher
     * @return Response
     */
    public function show(Voucher $voucher): Response
    {
        return response([
            'data' => VoucherResource::make($voucher),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Voucher $voucher
     * @return Response
     */
    public function destroy(Voucher $voucher): Response
    {
        $voucher->delete();
        return response([
            'message' => 'Voucher deleted successfully',
        ], 200);
    }
}
