<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        return response()->json(Category::all(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create($data);
        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        return response()->json($category, 200);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($data);
        return response()->json($category, 200);
    }

    public function destroy(Category $category)
    {

        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete a category with products'
            ], 409);
        }

        $category->delete();
        return response()->json([
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
