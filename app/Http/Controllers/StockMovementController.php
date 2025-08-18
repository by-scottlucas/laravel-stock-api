<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Enums\MovementTypeEnum;
use Illuminate\Http\Request;
use App\Http\Resources\StockMovementResource;
use App\Http\Resources\StockMovementCollection;
use App\Http\Resources\ProductResource;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::all();
        return new StockMovementCollection($movements);
    }

    public function show($id)
    {
        $movement = StockMovement::find($id);
        if (!$movement) {
            return response()->json([
                'message' => 'Stock movement not found'
            ], 404);
        }

        return new StockMovementResource($movement);
    }

    public function stockIn(Request $request, $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $product->quantity += $data['quantity'];
        $product->save();

        StockMovement::create([
            'product_id' => $productId,
            'type' => MovementTypeEnum::IN,
            'quantity' => $data['quantity'],
            'reason' => $data['reason'] ?? 'Purchase',
        ]);

        return new ProductResource($product);
    }

    public function stockOut(Request $request, $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        if ($product->quantity < $data['quantity']) {
            return response()->json([
                'message' => 'Insufficient stock'
            ], 400);
        }

        $product->quantity -= $data['quantity'];
        $product->save();

        StockMovement::create([
            'product_id' => $productId,
            'type' => MovementTypeEnum::OUT,
            'quantity' => $data['quantity'],
            'reason' => $data['reason'] ?? 'Sale',
        ]);

        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $movement = StockMovement::find($id);

        if (!$movement) {
            return response()->json([
                'message' => 'Stock movement not found'
            ], 404);
        }

        $product = $movement->product;

        if ($product) {
            if ($movement->type === MovementTypeEnum::IN->value) {
                $product->quantity -= $movement->quantity;
            } else {
                if (($product->quantity + $movement->quantity) < 0) {
                    return response()->json([
                        'message' => 'Cannot delete stock-out, as it would cause a negative product quantity'
                    ], 400);
                }
                $product->quantity += $movement->quantity;
            }
            $product->save();
        }

        $movement->delete();

        return response()->json([
            'message' => 'Stock movement deleted successfully'
        ], 200);
    }
}
