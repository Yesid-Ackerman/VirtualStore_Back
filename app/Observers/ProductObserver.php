<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Log;

class ProductObserver
{

    public function created(Product $product)
    {
          if ($product instanceof Product) {
        return; // 🚫 No registrar logs para productos aquí
    }
        Log::create([
            'user_id'     => auth()->id() ?? 0,
            'action'      => 'created',
            'entity_type' => Product::class,
            'entity_id'   => $product->id,
            'changes'     => json_encode($product->getChanges()),
        ]);
    }

    public function updated(Product $product)
    {
          if ($product instanceof Product) {
        return; // 🚫 No registrar logs para productos aquí
    }
        Log::create([
            'user_id'     => auth()->id() ?? 0,
            'action'      => 'updated',
            'entity_type' => Product::class,
            'entity_id'   => $product->id,
            'changes'     => json_encode($product->getChanges()),
        ]);
    }
    public function deleted(Product $product)
    {
        Log::create([
            'user_id'     => auth()->id(),
            'action'      => 'deleted',
            'entity_type' => Product::class,
            'entity_id'   => $product->id,
            'changes'     => null,
        ]);
    }
}
