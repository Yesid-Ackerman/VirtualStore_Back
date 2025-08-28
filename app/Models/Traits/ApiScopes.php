<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ApiScopes
{
    /**
     * Scope para incluir relaciones dinámicamente
     */
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return $query;
        }

        $relations = explode(',', request('included')); // ejemplo: included=products,products.category
        $allowIncluded = collect($this->allowIncluded);

        foreach ($relations as $key => $relationship) {
            if (!$allowIncluded->contains($relationship)) {
                unset($relations[$key]);
            }
        }

        return $query->with($relations);
    }

    /**
     * Scope para aplicar filtros dinámicos
     */
    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return $query;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            // filtro especial (ejemplo relacionado con program)
            if ($filter === 'program_q') {
                $query->whereHas('program', function ($q) use ($value) {
                    $q->where('name', 'LIKE', '%' . $value . '%');
                });
            }

            // filtros normales
            if ($allowFilter->contains($filter) && $filter !== 'program_q') {
                $query->where($filter, 'LIKE', '%' . $value . '%');
            }
        }

        return $query;
    }
}
