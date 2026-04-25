<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clientsource;

class ClientsourceController extends Controller
{
    public function ClientsourceSection()
    {
        return view('backend.pages.clientsource');
    }

    public function ClientsourceList()
    {
        $rows = Clientsource::latest()->get();

        return response()->json([
            'status' => 'success',
            'rows' => $rows
        ]);
    }

    public function ClientsourceCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'work' => 'nullable|string|max:255',
            'price' => 'required|string|max:255',
        ]);

        Clientsource::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'work' => $request->work,
            'price' => $request->price,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Client source created successfully'
        ]);
    }

    public function ClientsourceById(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:clientsources,id',
        ]);

        $row = Clientsource::findOrFail($request->id);

        return response()->json([
            'status' => 'success',
            'row' => $row
        ]);
    }

    public function ClientsourceUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:clientsources,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'work' => 'nullable|string|max:255',
            'price' => 'required|string|max:255',
        ]);

        $clientsource = Clientsource::findOrFail($request->id);

        $clientsource->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'work' => $request->work,
            'price' => $request->price,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Client source updated successfully'
        ]);
    }

    public function ClientsourceDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:clientsources,id',
        ]);

        $clientsource = Clientsource::findOrFail($request->id);
        $clientsource->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Client source deleted successfully'
        ]);
    }
}
