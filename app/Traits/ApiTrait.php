<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

trait ApiTrait
{
    /**
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request()->included)) {
            return;
        }
        $relations = explode(',', request()->included); //convertir a array la cadena de texto

        $allowIncluded = collect($this->allowIncluded);
        foreach ($relations as $key => $relationship) {
            if (!$allowIncluded->contains($relationship)) {
                unset($relations[$key]);
            }
        }
        $query->with($relations);
    }
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeCode(Builder $query)
    {
        if (empty($this->allowFilters)) {
            return;
        }

        $filters = request()->only('invoice_serie', 'invoice_correlative');
        foreach ($filters as $key => $value) {
            if ($value) {
                $query->where($key, $value);
            }
        }
    }
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeBetweenDates(Builder $query)
    {
        if (empty(request()->start_date) || empty(request()->end_date)) {
            return;
        }
        $query->whereDate($this->table.'created_at', '>=', request()->start_date)
            ->whereDate($this->table.'created_at', '<=', request()->end_date);
    }
}
