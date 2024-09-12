<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Trait ApiTrait
 * @package App\Traits
 * @method static Builder included()
 * @method static Builder code()
 * @method static Builder betweenDates()
 * @method static Builder sort()
 * @mixin Builder
 *
 */
trait ApiTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncludeds) || empty(request()->included)) {
            return;
        }
        $relations = explode(',', request()->included); //convertir a array la cadena de texto

        $allowIncluded = collect($this->allowIncludeds);
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
        $query->whereDate($this->table . 'created_at', '>=', request()->start_date)
            ->whereDate($this->table . 'created_at', '<=', request()->end_date);
    }

    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSorts) || empty(request()->sort)) {
            return;
        }
        $sortFields = explode(',',request('sort')); //convertir a array la cadena de texto
        $allowSort = collect($this->allowSorts);
        foreach ($sortFields as $sortField) {
            $sortDirection = 'asc';
            if (str_starts_with($sortField, '-')) {
                $sortDirection = 'desc';
                $sortField = substr($sortField, 1);
            }
            if ($allowSort->contains($sortField)) {
                $query->orderBy($sortField, $sortDirection);
            }
        }
    }

    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    public function scopePerPaginate(Builder $query)
    {
        return $query->paginate(request()->paginate, ['*'], 'page', request()->page);
    }
}
