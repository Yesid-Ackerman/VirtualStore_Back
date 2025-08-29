<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class ModelObserver
{
    protected function log(Model $model, string $action)
    {
        $userId = auth()->id();

        if (!$userId) {
            // No hay usuario autenticado, no registrar log
            return;
        }

        Log::create([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'changes' => json_encode($model->getChanges())
        ]);
    }

    public function created(Product $product)
    {
        Log::create([
            'user_id'     => auth()->id(),
            'action'      => 'created',
            'entity_type' => Product::class,
            'entity_id'   => $product->id,
            'changes'     => json_encode($product->getAttributes()),
        ]);
    }
    public function updated(Model $model)
    {
        $this->log($model, 'updated');
    }

    public function deleted(Model $model)
    {
        $this->log($model, 'deleted');
    }
}
