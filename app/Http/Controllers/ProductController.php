<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
public function index()
{
    $products = Product::included()
        ->filter()
        ->sort()
        ->paginateData();

    return response()->json($products);
}

public function store(Request $request)
{
    $product = Product::create($request->all());

    Log::create([
        'user_id' => auth()->id(),
        'action' => 'create',
        'entity_type' => 'Product',
        'entity_id' => $product->id,
        'changes' => json_encode($product->toArray())
    ]);

    return response()->json($product, 201);
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);
    $original = $product->toArray();
    $product->update($request->all());

    Log::create([
        'user_id' => auth()->id(),
        'action' => 'update',
        'entity_type' => 'Product',
        'entity_id' => $product->id,
        'changes' => json_encode([
            'before' => $original,
            'after' => $product->toArray()
        ])
    ]);

    return response()->json($product);
}

public function destroy($id)
{
    $product = Product::findOrFail($id);
    $data = $product->toArray();
    $product->delete();

    Log::create([
        'user_id' => auth()->id(),
        'action' => 'delete',
        'entity_type' => 'Product',
        'entity_id' => $id,
        'changes' => json_encode($data)
    ]);

    return response()->json(['message' => 'Producto eliminado']);
}
}
