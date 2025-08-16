<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%");
        }

        if ($request->has('low_stock')) {
            $query->where('quantity', '<', $request->input('low_stock'));
        }

        if ($request->has('sort_by') && $request->has('order')) {
            $sortBy = $request->input('sort_by');
            $order = $request->input('order');
            $query->orderBy($sortBy, $order);
        }

        $products = $query->paginate($request->input('per_page', 10));

        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        $data = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'integer|min:0',
            'price' => 'numeric|min:0',
        ]);

        $product->update($data);

        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
