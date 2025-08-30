<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    // 📌 Listar todas las ventas
    public function index()
    {
        $sales = Sale::with('items.product')->get();

        return response()->json([
            'success' => true,
            'message' => 'Lista de ventas obtenida correctamente',
            'data' => $sales
        ]);
    }

    // 📌 Ver detalle de una venta
    public function show(Sale $sale)
    {
        $sale->load('items.product');

        return response()->json([
            'success' => true,
            'message' => 'Detalle de la venta',
            'data' => $sale
        ]);
    }

    // 📌 Registrar una nueva venta
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'status' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $total = 0;

            // Crear la venta
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'total' => 0 // lo calculamos después
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                // Validar stock
                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "No hay stock suficiente para el producto {$product->name}"
                    ], 400);
                }

                // Restar stock
                $product->stock -= $item['quantity'];
                $product->save();

                // Calcular subtotal
                $subtotal = $product->price_sell * $item['quantity'];
                $total += $subtotal;

                // Crear item de venta
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_sell' => $product->price_sell
                ]);
            }

            // Actualizar total de la venta
            $sale->total = $total;
            $sale->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada correctamente',
                'data' => $sale->load('items.product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la venta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 📌 Historial de ventas
    public function history()
    {
        $sales = Sale::with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Historial de ventas',
            'data' => $sales
        ]);
    }

    // 📌 Resumen diario
    public function dailySummary()
    {
        $today = now()->toDateString();

        $sales = Sale::whereDate('created_at', $today)->get();

        $summary = [
            'fecha' => $today,
            'total_ventas' => $sales->count(),
            'monto_total' => $sales->sum('total'),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Resumen diario de ventas',
            'data' => $summary
        ]);
    }
}
