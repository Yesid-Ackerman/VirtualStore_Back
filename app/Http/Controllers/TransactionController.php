<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
     public function index()
    {
        return response()->json(
            Transaction::with(['product', 'user', 'type'])->get(),
            200
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id'    => 'required|exists:users,id',
            'type_id'    => 'required|exists:transaction_types,id',
            'quantity'   => 'required|integer|min:1',
            'reason'     => 'nullable|string',
        ]);

        $transaction = Transaction::create($validated);

        // actualizar stock automáticamente
        $product = $transaction->product;
        if ($transaction->type->name === 'entrada') {
            $product->stock += $transaction->quantity;
        } else {
            $product->stock -= $transaction->quantity;
        }
        $product->save();

        return response()->json($transaction->load(['product','user','type']), 201);
    }

    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load(['product','user','type']), 200);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json(['message' => 'Transacción eliminada'], 200);
    }
}
