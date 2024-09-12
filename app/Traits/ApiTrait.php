<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
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
    //enpoint que me filtre por serie, correlative,fecha inicio y fecha fin
    public function scopeCode(Builder $query)
    {
        if (empty($this->allowFilters)) {
            return;
        }

        $filters = request()->only($this->allowFilters);
        foreach ($filters as $key => $value) {
            if ($value) {
                $query->where($key, $value);
            }
        }

    }
}
