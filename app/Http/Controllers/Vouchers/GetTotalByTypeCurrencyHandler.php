<?php

namespace App\Http\Controllers\Vouchers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GetTotalByTypeCurrencyHandler
{
    public function __invoke(): Response
    {
        $totales = DB::table('vouchers')
            ->select('invoice_type_currency')
            ->selectRaw('SUM(total_amount) as total_amount')
            ->groupBy('invoice_type_currency')
            ->pluck('total_amount', 'invoice_type_currency');

        // Verifica si hay resultados
        if ($totales->isEmpty()) {
            return response([
                'message' => 'No se encontraron vouchers',
            ], 404);
        }

        return response([
            'data' => $totales,
        ], 200);
    }
}
