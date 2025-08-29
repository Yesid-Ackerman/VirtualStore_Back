<?php

namespace App\Observers;

use App\Models\Sale;
use App\Models\Log;

class SaleObserver
{
    public function created(Sale $sale)
    {
        Log::create([
            'user_id'     => auth()->id() ?? 0,
            'action'      => 'created',
            'entity_type' => Sale::class,
            'entity_id'   => $sale->id,
            'changes'     => json_encode($sale->getAttributes()),
        ]);
    }

    public function updated(Sale $sale)
    {
        Log::create([
            'user_id'     => auth()->id() ?? 0,
            'action'      => 'updated',
            'entity_type' => Sale::class,
            'entity_id'   => $sale->id,
            'changes'     => json_encode($sale->getChanges()),
        ]);
    }

    public function deleted(Sale $sale)
    {
        Log::create([
            'user_id'     => auth()->id() ?? 0,
            'action'      => 'deleted',
            'entity_type' => Sale::class,
            'entity_id'   => $sale->id,
            'changes'     => null,
        ]);
    }
}
