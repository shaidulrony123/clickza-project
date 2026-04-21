<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marketplace;

class MarketplaceController extends Controller
{
    // marketplace section
    public function MarketplaceSection()
    {
        return view('backend.pages.marketplacesection');
    }
    // marketplace list
    public function MarketplaceList()
{
    $rows = Marketplace::latest()->get();

    return response()->json([
        'status' => 'success',
        'rows' => $rows
    ]);
}

// marketplace create
public function MarketplaceCreate(Request $request)
{
    $request->validate([
        'user_name' => 'required|string|max:255',
        'description' => 'required|string',
        'url' => 'required|url',
        'badge' => 'nullable|string|max:255',
        'tags' => 'required|array|min:1',
        'tags.*' => 'required|string|max:255',
        'item' => 'nullable|integer',
        'sales' => 'nullable|integer',
        'rating' => 'nullable|numeric',
        'is_active' => 'required|in:0,1',
    ]);

    Marketplace::create([
        'user_name' => $request->user_name,
        'description' => $request->description,
        'url' => $request->url,
        'badge' => $request->badge,
        'tag' => json_encode($request->tags),
        'item' => $request->item ?? 0,
        'sales' => $request->sales ?? 0,
        'rating' => $request->rating ?? 0,
        'is_active' => $request->is_active,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Marketplace profile created successfully'
    ]);
}
// marketplace by id


public function MarketplaceById(Request $request)
{
    $row = Marketplace::find($request->id);

    if (!$row) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Marketplace profile not found'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'row' => $row
    ]);
}

public function MarketplaceUpdate(Request $request)
{
    $request->validate([
        'id' => 'required|exists:marketplaces,id',
        'user_name' => 'required|string|max:255',
        'description' => 'required|string',
        'url' => 'required|url',
        'badge' => 'nullable|string|max:255',
        'tags' => 'required|array|min:1',
        'tags.*' => 'required|string|max:255',
        'item' => 'nullable|integer',
        'sales' => 'nullable|integer',
        'rating' => 'nullable|numeric',
        'is_active' => 'required|in:0,1',
    ]);

    $marketplace = Marketplace::find($request->id);

    if (!$marketplace) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Marketplace profile not found'
        ], 404);
    }

    $marketplace->update([
        'user_name' => $request->user_name,
        'description' => $request->description,
        'url' => $request->url,
        'badge' => $request->badge,
        'tag' => json_encode($request->tags),
        'item' => $request->item ?? 0,
        'sales' => $request->sales ?? 0,
        'rating' => $request->rating ?? 0,
        'is_active' => $request->is_active,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Marketplace profile updated successfully'
    ]);
}
// marketplace delete
public function MarketplaceDelete(Request $request)
{
    $marketplace = Marketplace::find($request->id);

    if (!$marketplace) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Marketplace profile not found'
        ], 404);
    }

    $marketplace->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Marketplace profile deleted successfully'
    ]);
}
}
