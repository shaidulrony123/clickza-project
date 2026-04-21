<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //category section
    public function CategorySection(){
        return view('backend.pages.categorysection');
    }

    // category list
    public function CategoryList()
{
    $rows = Category::latest()->get();

    return response()->json([
        'status' => 'success',
        'rows' => $rows
    ]);
}

// category create
public function CategoryCreate(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name',
    ]);

    Category::create([
        'name' => $request->name,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Category created successfully',
    ]);
}

// category by id
public function CategoryById(Request $request)
{
    $row = Category::find($request->id);

    if (!$row) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Category not found'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'row' => $row
    ]);
}

public function CategoryUpdate(Request $request)
{
    $request->validate([
        'id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255|unique:categories,name,' . $request->id,
    ]);

    $category = Category::find($request->id);

    $category->update([
        'name' => $request->name
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Category updated successfully'
    ]);
}

// category delete
public function CategoryDelete(Request $request)
{
    $category = Category::find($request->id);

    if (!$category) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Category not found'
        ], 404);
    }

    $category->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Category deleted successfully'
    ]);
}
}
