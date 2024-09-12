<?php

namespace App\Http\Requests\Vouchers;

use Illuminate\Foundation\Http\FormRequest;

class GetVouchersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['required', 'int', 'gt:0'],
            'paginate' => ['required', 'int', 'gt:0'],
            'included' => ['nullable','string'],
            'serie' => ['nullable','required_with:correlative', 'string'],
            'correlative' => ['nullable','required_with:serie', 'int'],
            'start_date' => ['nullable','date', 'date_format:Y-m-d'],
            'end_date' => ['nullable','date' , 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ];
    }
}
