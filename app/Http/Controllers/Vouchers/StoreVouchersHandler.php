<?php

namespace App\Http\Controllers\Vouchers;

use App\Http\Resources\Vouchers\VoucherResource;
use App\Jobs\Vouchers\ProcessVouchersFromXmlContentsJob;
use App\Services\VoucherService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StoreVouchersHandler
{
    public function __construct(private readonly VoucherService $voucherService) {}

    public function __invoke(Request $request): Response
    {
        try {
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

        } catch (Exception $exception) {
            Log::error($exception);
            return response([
                'message' => $exception->getMessage(),
            ], 400);
        }
    }
}
