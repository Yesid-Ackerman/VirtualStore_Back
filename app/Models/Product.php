<?php

namespace App\Models;

use App\Models\Traits\ApiScopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'brand',
        'model',
        'sku',
        'price_buy',
        'price_sell',
        'stock',
        'status',
        'avatar'
    ];
    protected $allowSort = [
        'id',
        'name',
        'brand',
        'model',
        'sku',
        'price_buy',
        'price_sell',
        'stock',
        'status',
        'category_id',
        'created_at',
        'updated_at'
    ];
    protected $allowIncluded = ['category'];
    protected $allowFilter = ['name', 'brand', 'model', 'sku'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
    /* SCOPES */
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return;
        }

        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);

        foreach ($relations as $key => $relationship) {
            if (!$allowIncluded->contains($relationship)) {
                unset($relations[$key]);
            }
        }
        $query->with($relations);
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                $query->where($filter, 'LIKE', "%{$value}%");
            }
        }
    }

    public function scopeSort(Builder $query)
    {
        $sort = request('sort');
        $allowed = collect($query->getModel()->allowSort ?? []);

        if (!$sort || $allowed->isEmpty()) {
            return $query; // sin orden si no hay campos permitidos o no se mandó sort
        }

        foreach (explode(',', $sort) as $field) {
            $direction = 'asc';

            if (str_starts_with($field, '-')) {
                $direction = 'desc';
                $field = ltrim($field, '-');
            }

            if ($allowed->contains($field)) {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }
    public function scopePaginateData($query)
    {
        $perPage = intval(request('per_page')) ?: 1; // default = 10
        return $query->paginate($perPage);
    }
}
