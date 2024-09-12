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
    public function scopeIncluded(Builder $query, $included)
    {
        if (empty($this->allowIncluded) || empty($included)) {
            return;
        }
        $relations = explode(',', $included); //convertir a array la cadena de texto

        $allowIncluded = collect($this->allowIncluded);
        foreach ($relations as $key => $relationship) {
            if (!$allowIncluded->contains($relationship)) {
                unset($relations[$key]);
            }
        }
        $query->with($relations);
    }
}
