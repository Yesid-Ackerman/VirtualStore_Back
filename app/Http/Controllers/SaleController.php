<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,transfer',
            'status' => 'required|in:paid,pending,canceled',
        ]);

        DB::beginTransaction();

        try {
            // Crear la venta
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'total' => 0, // se calcula luego
                'payment_method' => $request->payment_method,
                'status' => $request->status,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Precio actual del producto
                $price = $product->price_sell;

                // Validar stock
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para el producto {$product->name}");
                }

                // Registrar item en la venta
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_sell' => $price,
                ]);

                // Reducir stock
                $product->stock -= $item['quantity'];
                $product->save();

                // Registrar movimiento de stock
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'salida',
                    'quantity' => $item['quantity'],
                    'description' => 'Venta #' . $sale->id,
                ]);

                // Acumular total
                $total += $price * $item['quantity'];
            }

            // Actualizar total de la venta
            $sale->update(['total' => $total]);

            DB::commit();

            return response()->json([
                'message' => 'Venta registrada exitosamente',
                'sale' => $sale->load('items')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

public function history()
{
    $sales = Sale::with(['user', 'items.product'])->get();

    $historial = [];

    foreach ($sales as $sale) {
        foreach ($sale->items as $item) {
            $historial[] = [
                'id'             => $sale->id,
                'hora'           => $sale->created_at->format('Y-m-d H:i:s'),
                'usuario'        => $sale->user->name,
                'producto'       => $item->product->name,
                'cantidad'       => $item->quantity,
                'precio_venta'   => $item->price_sell,
                'metodo_pago'    => $sale->payment_method,
            ];
        }
    }

    return response()->json($historial);
}
}
