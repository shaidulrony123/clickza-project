<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Exception;

class ProductController extends Controller
{
    //product section
    public function ProductSection(){
        return view('backend.pages.productsection');    
    }
    // product list
    public function ProductList()
{
    $rows = Product::latest()->get();

    return response()->json([
        'status' => 'success',
        'rows' => $rows
    ]);
}
// product create
public function ProductCreate(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'badge' => 'nullable|string|max:255',
        'description' => 'required|string',
        'long_description' => 'required|string',
        'price' => 'required|numeric',
        'discount' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        'tags' => 'required|array|min:1',
        'tags.*' => 'required|string|max:255',
        'icon' => 'nullable|string|max:255',
        'live_link' => 'nullable|url',
        'status' => 'required|in:0,1',
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();

        $destinationPath = public_path('frontend/images/product');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $image->move($destinationPath, $imageName);

        $imagePath = 'frontend/images/product/' . $imageName;
    }

    Product::create([
        'badge' => $request->badge,
        'name' => $request->name,
        'description' => $request->description,
        'long_description' => $request->long_description,
        'price' => $request->price,
        'discount' => $request->discount ?? 0,
        'image' => $imagePath,
        'tag' => json_encode($request->tags),
        'icon' => $request->icon,
        'live_link' => $request->live_link,
        'status' => $request->status,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Product created successfully'
    ]);
}
// product by id
public function ProductById(Request $request)
{
    $request->validate([
        'id' => 'required|exists:products,id',
    ]);

    $row = Product::find($request->id);

    if (!$row) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Product not found'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'row' => $row
    ]);
}

public function ProductUpdate(Request $request)
{
    $request->validate([
        'id' => 'required|exists:products,id',
        'name' => 'required|string|max:255',
        'badge' => 'nullable|string|max:255',
        'description' => 'required|string',
        'long_description' => 'required|string',
        'price' => 'required|numeric',
        'discount' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        'tags' => 'required|array|min:1',
        'tags.*' => 'required|string|max:255',
        'icon' => 'nullable|string|max:255',
        'live_link' => 'nullable|url',
        'status' => 'required|in:0,1',
    ]);

    $product = Product::find($request->id);

    if (!$product) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Product not found'
        ], 404);
    }

    $imagePath = $product->image;

    if ($request->hasFile('image')) {
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();

        $destinationPath = public_path('frontend/images/product');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $image->move($destinationPath, $imageName);
        $imagePath = 'frontend/images/product/' . $imageName;
    }

    $product->update([
        'badge' => $request->badge,
        'name' => $request->name,
        'description' => $request->description,
        'long_description' => $request->long_description,
        'price' => $request->price,
        'discount' => $request->discount ?? 0,
        'image' => $imagePath,
        'tag' => json_encode($request->tags),
        'icon' => $request->icon,
        'live_link' => $request->live_link,
        'status' => $request->status,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Product updated successfully'
    ]);
}

// product delete
public function ProductDelete(Request $request)
    {
        try {
            // Find the advertisement by ID
            $product = Product::find($request->input('id'));
    
            if ($product) {
                // Get the image path from the advertisement record
                $imagePath = public_path($product->image);
    
                // Delete the image from the server if it exists
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
    
                // Delete the product record
                $product->delete();
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'product deleted'
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'product not found'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }
}
