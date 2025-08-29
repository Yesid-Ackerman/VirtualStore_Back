<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Aumentar stock
        $product->stock += $request->quantity;
        $product->save();

        // Registrar movimiento de stock
        $movement = StockMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => 'entrada',
            'quantity' => $request->quantity,
            'description' => $request->description ?? 'Ingreso de stock',
        ]);

        return response()->json([
            'message' => 'Stock actualizado correctamente',
            'movement' => $movement,
            'product' => $product
        ], 201);
    }
}
