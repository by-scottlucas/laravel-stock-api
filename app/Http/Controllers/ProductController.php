<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        if ($request->has('category')) {
            $categoryName = $request->input('category');
            $category = Category::where('name', $categoryName)->first();

            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

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

        return new ProductCollection($products);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_name' => 'nullable|string|max:255',
        ]);

        if ($request->has('category_name') && $request->input('category_name')) {
            $category = Category::firstOrCreate(['name' => $request->input('category_name')]);
            $data['category_id'] = $category->id;
        }

        $product = Product::create($data);

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return new ProductResource($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $data = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'integer|min:0',
            'price' => 'numeric|min:0',
            'category_name' => 'nullable|string|max:255',
        ]);

        if ($request->has('category_name')) {
            if ($request->input('category_name')) {
                $category = Category::firstOrCreate([
                    'name' => $request->input('category_name')
                ]);
                $data['category_id'] = $category->id;
            } else {
                $data['category_id'] = null;
            }
        }

        $product->update($data);

        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
